@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-ticket-types.css') }}">
@endsection

@section('content')
<div class="ticket-type-container">
  <h1 class="page-title">
    {{ $event->title }}｜券種管理
  </h1>

  {{-- 一覧 --}}
  <table class="ticket-table">
    <tr>
      <th>券種名</th>
      <th>価格</th>
      <th>販売数</th>
    </tr>

    @forelse ($ticketTypes as $ticketType)
      <tr>
        <td>{{ $ticketType->type }}</td>
        <td>¥{{ number_format($ticketType->price) }}</td>
        <td>{{ $ticketType->stock }}</td>
      </tr>
    @empty
  <tr>
    <td colspan="3" class="empty">
      まだ券種が登録されていません
    </td>
  </tr>
@endforelse
  </table>

  {{-- 追加フォーム --}}
<h2 class="section-title">券種を追加</h2>

<form
  method="POST"
  action="{{ route('admin.events.ticket-types.store', $event)  }}"
  class="ticket-form"
>
  @csrf

  <div class="form-row">
    <select name="event_schedule_id" required>
      <option value="">日程を選択</option>
      @foreach ($event->schedules as $schedule)
        <option value="{{ $schedule->id }}">
          {{ \Carbon\Carbon::parse($schedule->start_time)->format('Y/m/d H:i') }}
        </option>
      @endforeach
    </select>

    <input type="text" name="type" placeholder="券種名" required>
    <input type="number" name="price" placeholder="価格" required>
    <input type="number" name="stock" placeholder="販売数" required>

    <button type="submit">追加</button>
  </div>
</form>

 <div class="below-link">
  <a href="{{ route('admin.events.edit', $event) }}">
    ← 基本情報画面へ戻る
  </a>
</div>
</div>
@endsection
