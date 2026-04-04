@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-reservation-edit.css') }}">
@endsection

@section('content')
<div class="edit-container">

  <h1 class="edit-title">予約編集</h1>

  <form method="POST"
    action="{{ route('admin.reservations.update', $order) }}"
    class="edit-form">
    @csrf
    @method('PATCH')

    <div class="form-group">
      <label>お名前</label>
      <input type="text" name="name"
        value="{{ $order->name }}" required>
    </div>

    <div class="form-group">
      <label>メール</label>
      <input type="email" name="email"
        value="{{ $order->email }}" required>
    </div>

    <div class="form-group">
      <label>枚数</label>
      <input type="number" name="ticket_quantity"
        value="{{ $order->ticket_quantity }}"
        min="1" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-update">
        更新する
      </button>
    </div>

  </form>

  <div class="back-link-area">
    <a href="{{ route('admin.events.reservations.index', $order->schedule->event) }}"
       class="back-link">
      ← 予約一覧へ戻る
    </a>
  </div>

</div>
@endsection