@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-event-show.css') }}">
@endsection

@section('content')
<div class="event-manage-container">
  <h1 class="page-title">{{ $event->title }} 管理ページ</h1>

<div class="manage-menu">
    <a href="{{ route('admin.events.edit', $event) }}" class="manage-item">
    <span class="manage-title">公演基本情報</span>
    <span class="manage-desc">公演名・団体名・説明文</span>
  </a>
 
  <a href="{{ route('admin.events.reservation.create', $event) }}" class="manage-item">
    <span class="manage-title">予約フォーム作成</span>
    <span class="manage-desc">公開用フォームを作成・確認</span>
  </a>

  <a href="{{ route('admin.events.reservation.preview', $event) }}" class="manage-item">
    <span class="manage-title">公開予約フォーム確認</span>
    <span class="manage-desc">実際の予約画面・レイアウト確認</span>
  </a>

  <a href="{{ route('admin.events.reservations.index', $event) }}" class="manage-item">
    <span class="manage-title">予約管理一覧</span>
    <span class="manage-desc">予約状況・購入者一覧</span>
  </a>

  <a href="{{ route('admin.events.reservations.waiting', $event) }}" class="manage-item">
  <span class="manage-title">振込待ち一覧</span>
  <span class="manage-desc">未入金予約の確認・入金処理</span>
</a>
</div>

</div>
@endsection
