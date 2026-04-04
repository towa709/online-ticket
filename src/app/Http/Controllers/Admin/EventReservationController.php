<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EventReservationController extends Controller
{
  public function create(Event $event)
  {
    $ticketTypes = $event->schedules
      ->flatMap->ticketTypes
      ->unique('type')
      ->values();

    return view(
      'admin.events.reservation.create',
      compact('event', 'ticketTypes')
    );
  }

  public function store(Request $request, Event $event)
  {
    $request->validate([
      'reservation_image_1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
      'reservation_image_2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // =========================
    // 予約開始日時
    // =========================
    $startAt = null;
    if ($request->reservation_start_date && $request->reservation_start_time) {
      $startAt = Carbon::parse(
        $request->reservation_start_date . ' ' . $request->reservation_start_time
      );
    }

    // =========================
    // 予約終了日時
    // =========================
    $endAt = null;
    if (
      $request->reservation_end_type === 'datetime'
      && $request->reservation_end_date
      && $request->reservation_end_time
    ) {
      $endAt = Carbon::parse(
        $request->reservation_end_date . ' ' . $request->reservation_end_time
      );
    }

    $event->reservation_start_at = $startAt;
    $event->reservation_end_at   = $endAt;

    // =========================
    // 🔥 画像削除処理
    // =========================

    if ($request->delete_reservation_image_1) {
      if ($event->reservation_image_1) {
        Storage::disk('public')->delete($event->reservation_image_1);
      }
      $event->reservation_image_1 = null;
    }

    if ($request->delete_reservation_image_2) {
      if ($event->reservation_image_2) {
        Storage::disk('public')->delete($event->reservation_image_2);
      }
      $event->reservation_image_2 = null;
    }

    // =========================
    // 🔥 画像アップロード処理（上書き対応）
    // =========================

    if ($request->hasFile('reservation_image_1')) {

      // 旧画像削除
      if ($event->reservation_image_1) {
        Storage::disk('public')->delete($event->reservation_image_1);
      }

      $path1 = $request->file('reservation_image_1')
        ->store('flyers', 'public');

      $event->reservation_image_1 = $path1;
    }

    if ($request->hasFile('reservation_image_2')) {

      if ($event->reservation_image_2) {
        Storage::disk('public')->delete($event->reservation_image_2);
      }

      $path2 = $request->file('reservation_image_2')
        ->store('flyers', 'public');

      $event->reservation_image_2 = $path2;
    }

    $event->allow_cash = $request->has('allow_cash');
    $event->allow_transfer = $request->has('allow_transfer');
    $event->save();

    return redirect()
      ->route('admin.events.reservation.create', $event)
      ->with('success', '保存しました');
  }

  public function preview(Event $event)
  {
    $ticketTypes = $event->schedules
      ->flatMap->ticketTypes
      ->unique('type')
      ->values();

    return view(
      'admin.events.reservation.preview',
      compact('event', 'ticketTypes')
    );
  }

  public function index(Request $request, Event $event)
  {
    $query = $event->orders()
      ->with(['schedule', 'tickets.ticketType']);

    // 名前検索
    if ($request->filled('keyword')) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->keyword . '%')
          ->orWhere('email', 'like', '%' . $request->keyword . '%');
      });
    }

    // 公演日検索
    if ($request->filled('schedule_id')) {
      $query->where('event_schedule_id', $request->schedule_id);
    }

    // 券種検索
    if ($request->filled('ticket_type_id')) {
      $query->whereHas('tickets', function ($q) use ($request) {
        $q->where('ticket_type_id', $request->ticket_type_id);
      });
    }

    $orders = $query->latest()->paginate(15)->withQueryString();

    $schedules = $event->schedules()->orderBy('start_time')->get();

    $ticketTypes = $event->schedules
      ->flatMap->ticketTypes
      ->unique('type')   // ← idではなく typeでユニーク化
      ->values();

    return view('admin.events.reservations.index', compact(
      'event',
      'orders',
      'schedules',
      'ticketTypes'
    ));
  }
}
