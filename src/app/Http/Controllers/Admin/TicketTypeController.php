<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
  public function index(Event $event)
  {
    $ticketTypes = TicketType::whereHas('eventSchedule', function ($q) use ($event) {
      $q->where('event_id', $event->id);
    })->get();

    return view(
      'admin.ticket-types.index',
      compact('event', 'ticketTypes')
    );
  }

  public function store(Request $request, Event $event)

  {
    TicketType::create([
      'event_schedule_id' => $request->event_schedule_id,
      'type'              => $request->type,
      'price'             => $request->price,
      'stock'             => $request->stock,
    ]);

    return redirect()
      ->route('admin.events.ticket-types.index', $event)
      ->with('success', '券種を追加しました');
  }
}
