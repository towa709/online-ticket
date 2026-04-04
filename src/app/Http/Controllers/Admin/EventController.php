<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Requests\Admin\StoreEventRequest;

class EventController extends Controller
{
  public function index()
  {
    $events = Event::orderBy('created_at', 'desc')->get();
    return view('admin.events.index', compact('events'));
  }

  // 新規公演登録画面
  public function create()
  {
    return view('admin.events.create');
  }

  // 登録処理
  public function store(StoreEventRequest $request)
  {
    $reservationUrl =
      config('services.reservation.base_url') . $request->reservation_form_url;

    $event = Event::create([
      'title' => $request->title,
      'display_organization_name' => $request->organization_name,
      'reservation_form_url' => $reservationUrl,
    ]);

    return redirect()->route('admin.events.show', $event->id);
  }

  // 公演詳細管理TOP
  public function show(Event $event)
  {
    return view('admin.events.show', compact('event'));
  }

  // 公演基本情報 編集画面
  public function edit(Event $event)
  {
    $event->load([
      'schedules.ticketTypes'
    ]);

    return view('admin.events.edit', compact('event'));
  }
}
