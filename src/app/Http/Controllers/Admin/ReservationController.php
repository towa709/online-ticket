<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransferCompletedMail;
use App\Mail\ReservationUpdated;
use App\Mail\ReservationCancelled;


class ReservationController extends Controller
{
  public function cancel(Order $order)
  {
    if ($order->status === 'cancelled') {
      return response()->json(['message' => 'already cancelled'], 400);
    }

    $order->update([
      'status' => 'cancelled',
    ]);

    Mail::to($order->email)->send(
      new ReservationCancelled($order)
    );

    return response()->json([
      'status' => 'cancelled',
    ]);
  }

  public function exportCsv(Request $request, Event $event)
  {
    $query = $event->orders()
      ->with(['schedule', 'tickets.ticketType']);

    if ($request->filled('keyword')) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->keyword . '%')
          ->orWhere('email', 'like', '%' . $request->keyword . '%');
      });
    }

    if ($request->filled('schedule_id')) {
      $query->where('event_schedule_id', $request->schedule_id);
    }

    if ($request->filled('ticket_type_id')) {
      $query->whereHas('tickets', function ($q) use ($request) {
        $q->where('ticket_type_id', $request->ticket_type_id);
      });
    }

    $orders = $query->latest()->get();

    $response = new StreamedResponse(function () use ($orders) {

      $handle = fopen('php://output', 'w');

      // 🔥 これが重要（BOM追加）
      fwrite($handle, "\xEF\xBB\xBF");

      fputcsv($handle, [
        '予約日時',
        '公演日時',
        '会場',
        '予約者',
        'メール',
        '券種内訳',
        '合計枚数',
        '状態',
      ]);

      foreach ($orders as $order) {

        $ticketSummary = $order->tickets
          ->map(fn($t) => "{$t->ticketType->type}×{$t->quantity}")
          ->implode(' / ');

        fputcsv($handle, [
          $order->created_at->format('Y-m-d H:i'),
          $order->schedule->start_time->format('Y-m-d H:i'),
          $order->schedule->venue,
          $order->name,
          $order->email,
          $ticketSummary,
          $order->ticket_quantity,
          match ($order->status) {
            'waiting_payment' => '振込待ち',
            'purchased' => '予約済',
            'cancelled' => 'キャンセル済',
            default => $order->status,
          },
        ]);
      }

      fclose($handle);
    });

    $filename = 'reservations_' . now()->format('Ymd_His') . '.csv';

    $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
    $response->headers->set(
      'Content-Disposition',
      "attachment; filename={$filename}"
    );

    return $response;
  }

  public function index(Request $request, Event $event)
  {
    $query = $event->orders()
      ->with(['schedule', 'tickets.ticketType']);

    // 名前・メール検索（部分一致）
    if ($request->filled('keyword')) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->keyword . '%')
          ->orWhere('email', 'like', '%' . $request->keyword . '%');
      });
    }

    // 公演日で絞り込み
    if ($request->filled('schedule_id')) {
      $query->where('event_schedule_id', $request->schedule_id);
    }

    // 券種で絞り込み
    if ($request->filled('ticket_type_id')) {
      $query->whereHas('tickets', function ($q) use ($request) {
        $q->where('ticket_type_id', $request->ticket_type_id);
      });
    }

    $orders = $query->latest()->get();

    // 検索用データ
    $schedules = $event->schedules()->orderBy('start_time')->get();
    $ticketTypes = $event->ticketTypes()->get();

    return view('admin.events.reservations.index', compact(
      'event',
      'orders',
      'schedules',
      'ticketTypes'
    ));
  }

  public function waitingList(Event $event)
  {
    $orders = $event->orders()
      ->where('status', 'waiting_payment')
      ->with(['schedule', 'tickets.ticketType'])
      ->latest()
      ->get();

    return view(
      'admin.events.reservations.waiting',
      compact('event', 'orders')
    );
  }

  public function confirmPayment(Order $order)
  {
    if ($order->status !== 'waiting_payment') {
      return back();
    }

    $order->update([
      'status' => 'purchased',
      'transfer_due_at' => null,
    ]);

    Mail::to($order->email)->send(
      new TransferCompletedMail($order)
    );

    return back()->with('success', '入金確認しました');
  }

  public function edit(Order $order)
  {
    return view('admin.events.reservations.edit', compact('order'));
  }

  public function update(Request $request, Order $order)
  {
    $validated = $request->validate([
      'name' => 'required|string',
      'email' => 'required|email',
      'ticket_quantity' => 'required|integer|min:1',
    ]);

    $order->update($validated);

    Mail::to($order->email)->send(
      new ReservationUpdated($order)
    );

    return redirect()->route(
      'admin.events.reservations.index',
      $order->schedule->event
    )->with('success', '予約を更新しました');
  }
}
