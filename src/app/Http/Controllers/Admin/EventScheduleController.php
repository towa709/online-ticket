<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventScheduleController extends Controller
{
  public function index(Event $event)
  {
    $schedules = EventSchedule::where('event_id', $event->id)
      ->orderBy('start_time')
      ->get();

    return view(
      'admin.events.schedules.index',
      compact('event', 'schedules')
    );
  }

  public function store(Request $request, Event $event)
  {
    $startTime = Carbon::parse(
      $request->date . ' ' . $request->hour . ':' . $request->minute
    );

    EventSchedule::create([
      'event_id'      => $event->id,
      'start_time'    => $startTime,
      'venue'         => $request->venue,

      'sale_start'    => now(),
      'sale_end'      => $startTime->copy()->subDay(),

      'seat_limit'    => $request->seat_limit,
      'seat_capacity' => $request->seat_limit,
    ]);

    return redirect()
      ->route('admin.events.schedules.index', $event)
      ->with('success', '日程を追加しました');
  }

  public function destroy(Event $event, EventSchedule $schedule)
  {
    if ($schedule->event_id !== $event->id) {
      abort(404);
    }

    $schedule->delete();

    return redirect()
      ->route('admin.events.schedules.index', $event)
      ->with('success', '日程を削除しました');
  }

  public function update(Request $request, Event $event, EventSchedule $schedule)
  {
    if ($schedule->event_id !== $event->id) {
      abort(404);
    }

    $startTime = Carbon::parse(
      $request->date . ' ' . $request->hour . ':' . $request->minute
    );

    $schedule->update([
      'start_time'    => $startTime,
      'venue'         => $request->venue,
      'seat_limit'    => $request->seat_limit,
      'seat_capacity' => $request->seat_limit,
    ]);

    return redirect()
      ->route('admin.events.schedules.index', $event)
      ->with('success', '日程を変更しました');
  }
}
