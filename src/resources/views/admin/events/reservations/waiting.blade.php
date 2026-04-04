@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-waiting.css') }}">
@endsection

@section('content')
<div class="waiting-container">

  <h1 class="waiting-title">振込待ち一覧</h1>

  @if($orders->isEmpty())
    <p class="empty-text">振込待ちはありません。</p>
  @else
    <div class="table-wrapper">
      <table class="waiting-table">
        <thead>
          <tr>
            <th>予約者</th>
            <th>メール</th>
            <th>公演日時</th>
            <th>期限</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
            @php
              $isExpired = $order->transfer_due_at && now()->gt($order->transfer_due_at);
            @endphp
            <tr>
              <td>{{ $order->name }}</td>
              <td>{{ $order->email }}</td>
              <td>
                {{ $order->schedule->start_time->format('Y-m-d H:i') }}
              </td>
              <td>
                @if($order->transfer_due_at)
                  @if($isExpired)
                    <span class="expired">
                      {{ $order->transfer_due_at->format('Y-m-d H:i') }}
                      （期限切れ）
                    </span>
                  @else
                    {{ $order->transfer_due_at->format('Y-m-d H:i') }}
                  @endif
                @else
                  —
                @endif
              </td>
              <td>
                <form method="POST"
                  action="{{ route('admin.reservations.confirm', $order) }}">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="confirm-btn">
                    入金確認
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div class="back-link-area">
    <a href="{{ route('admin.events.show', $event) }}" class="back-link">
      ← 公演管理画面へ戻る
    </a>
  </div>

</div>
@endsection