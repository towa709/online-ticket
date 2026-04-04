@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-event-edit.css') }}">
@endsection

@section('content')
<div class="event-edit-wrapper">

  <div class="event-show-container">
    <h1 class="page-title">{{ $event->title }}｜公演基本情報</h1>

    {{-- 基本情報 --}}
    <section class="section-block">
      <div class="section-header">
        <h2>基本情報</h2>
        <a
          href="{{ route('admin.events.edit', $event) }}"
          class="edit-link"
        >設定</a>
      </div>

      <table class="info-table">
        <tr>
          <th>公演名</th>
          <td>{{ $event->title }}</td>
        </tr>
        <tr>
          <th>表示用団体名</th>
          <td>{{ $event->display_organization_name }}</td>
        </tr>
        <tr>
          <th>公開URL</th>
          <td>
            {{ config('services.reservation.base_url') }}{{ $event->reservation_form_url }}
          </td>
        </tr>
      </table>
    </section>

    {{-- 公演日程 --}}
{{-- 公演日程 --}}
<section class="section-block">
  <div class="section-header">
    <h2>公演日程</h2>
    <a href="{{ route('admin.events.schedules.index', $event) }}" class="manage-link">
      日程管理
    </a>
  </div>

  @if ($event->schedules->isEmpty())
    <p class="section-note">まだ公演日程が登録されていません。</p>
  @else
    <table class="summary-table">
      <thead>
        <tr>
          <th>日時</th>
          <th>会場</th>
          <th>座席数</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($event->schedules as $schedule)
          <tr>
            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('Y/m/d H:i') }}</td>
            <td>{{ $schedule->venue }}</td>
            <td>{{ $schedule->seat_limit }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</section>

    {{-- 料金テーブル --}}
<section class="section-block">
  <div class="section-header">
    <h2>料金テーブル（券種）</h2>
    <a href="{{ route('admin.events.ticket-types.index', $event) }}" class="manage-link">
      チケット管理
    </a>
  </div>

  @if ($event->schedules->isEmpty())
    <p class="section-note">日程が未登録です。</p>
  @else
    <table class="summary-table">
      <thead>
  <tr>
    <th>券種</th>
    @foreach ($event->schedules as $schedule)
      <th>
        {{ \Carbon\Carbon::parse($schedule->start_time)->format('m/d H:i') }}
      </th>
    @endforeach
  </tr>
</thead>

      <tbody>
        @foreach ($event->schedules->flatMap->ticketTypes->unique('type') as $type)
          <tr>
            <td>{{ $type->type }}<br>¥{{ number_format($type->price) }}</td>

            @foreach ($event->schedules as $schedule)
              <td class="center">
                {{ $schedule->ticketTypes->where('type', $type->type)->isNotEmpty() ? '●' : '—' }}
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</section>
  </div>

  <div class="below-link">
    <a href="{{ route('admin.events.show', $event) }}">
      ← 公演管理画面へ戻る
    </a>
  </div>

</div>
@endsection