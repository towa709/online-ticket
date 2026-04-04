@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation-form.css') }}">
@endsection

@section('content')
<div class="reservation-preview-container">
  <h1 class="page-title">
    {{ $event->title }}｜公開予約フォーム確認
  </h1>

  <p class="help-text">
    ※ 実際にお客様が見る予約フォームの表示確認用画面です。
  </p>

  {{-- ここに「公開予約フォーム」と同じBladeを読み込む --}}
    @include('admin.events.reservation.form', [
    'event' => $event,
    'ticketTypes' => $ticketTypes,
  ])

  {{-- 公演管理画面へ戻る --}}
  <div class="page-back-area">
    <a href="{{ route('admin.events.show', $event) }}" class="page-back-link">
      ← 公演管理画面へ戻る
    </a>
  </div>

</div>
@endsection
