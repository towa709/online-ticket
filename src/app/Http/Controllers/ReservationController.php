<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\TicketType;
use App\Mail\ReservationCompleted;
use App\Mail\TransferInstructionMail;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
  public function store(Request $request, Event $event)
  {
    $validated = $request->validate([
      'schedule_id'    => 'required|exists:event_schedules,id',
      'name'           => 'required|string',
      'email'          => 'required|email',
      'payment_method' => 'required|string',
      'tickets'        => 'required|array',
      'tickets.*'      => 'integer|min:0',
    ]);

    $totalQuantity = array_sum($validated['tickets']);

    // 🔥 銀行振込・コンビニ振込を振込扱いにする
    $isTransfer = $validated['payment_method'] === 'transfer';
    $order = DB::transaction(function () use ($validated, $totalQuantity, $isTransfer) {

      $status = $isTransfer ? 'waiting_payment' : 'purchased';
      $transferDueAt = $isTransfer ? now()->addDays(3) : null;

      $order = Order::create([
        'event_schedule_id' => $validated['schedule_id'],
        'name'              => $validated['name'],
        'email'             => $validated['email'],
        'ticket_quantity'   => $totalQuantity,
        'payment_method'    => $validated['payment_method'],
        'status'            => $status,
        'transfer_due_at'   => $transferDueAt,
      ]);

      foreach ($validated['tickets'] as $ticketTypeId => $quantity) {
        if ($quantity > 0) {
          $ticketType = TicketType::lockForUpdate()->findOrFail($ticketTypeId);

          if ($ticketType->stock < $quantity) {
            throw new \Exception('チケット在庫が不足しています');
          }

          $ticketType->decrement('stock', $quantity);

          OrderTicket::create([
            'order_id'       => $order->id,
            'ticket_type_id' => $ticketTypeId,
            'quantity'       => $quantity,
          ]);
        }
      }

      return $order;
    });

    // 🔥 振込案内メール送信
    if ($isTransfer) {
      Mail::to($order->email)->send(
        new TransferInstructionMail($order)
      );

      return redirect()->route('reservations.transfer', [
        'event' => $event,
        'order' => $order->id,
      ]);
    }

    // 🔥 即時確定メール
    Mail::to($order->email)->send(
      new ReservationCompleted($order)
    );

    return redirect()->route('reservations.complete', $event);
  }

  public function create(Event $event)
  {
    $ticketTypes = $event->schedules
      ->flatMap->ticketTypes
      ->unique('type')
      ->values();

    return view('reservations.create', compact('event', 'ticketTypes'));
  }
}
