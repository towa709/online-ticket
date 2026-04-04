@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-reservations.css') }}">
@endsection

@section('content')
<div class="admin-reservations-container">

  <h1 class="page-title">
    {{ $event->title }}｜予約一覧
  </h1>

  <div class="reservations-actions">

    <form method="GET" class="search-form">

      <input
        type="text"
        name="keyword"
        placeholder="名前・メール"
        value="{{ request('keyword') }}"
      >

      <select name="schedule_id">
        <option value="">公演日時</option>
        @foreach($schedules as $schedule)
          <option value="{{ $schedule->id }}"
            {{ request('schedule_id') == $schedule->id ? 'selected' : '' }}>
            {{ \Carbon\Carbon::parse($schedule->start_time)->format('n/j H:i') }}
          </option>
        @endforeach
      </select>

      <select name="ticket_type_id">
        <option value="">券種</option>
        @foreach($ticketTypes as $type)
          <option value="{{ $type->id }}"
            {{ request('ticket_type_id') == $type->id ? 'selected' : '' }}>
            {{ $type->type }}
          </option>
        @endforeach
      </select>

      <button type="submit">検索</button>

    </form>
  <div class="action-buttons">
    <button type="button" class="btn-print" onclick="window.print()">
      印刷
    </button>
  </div>

</div>

  @if ($orders->isEmpty())
    <p class="empty-text">まだ予約はありません。</p>
  @else
    <table class="reservations-table">
      <thead>
        <tr>
          <th>予約日時</th>
          <th>公演日時</th>
          <th>予約者</th>
          <th>メール</th>
          <th>枚数</th>
          <th>券種内訳</th>
          <th>状態</th>
          <th>詳細</th>
        </tr>
      </thead>
      <tbody>
  @foreach ($orders as $order)
    <tr
      class="reservation-row"
      data-id="{{ $order->id }}"
      data-name="{{ $order->name }}"
      data-email="{{ $order->email }}"
      data-created="{{ $order->created_at->format('Y/m/d H:i') }}"
      data-schedule="{{ \Carbon\Carbon::parse($order->schedule->start_time)->format('Y/m/d H:i') }}"
      data-venue="{{ $order->schedule->venue }}"
      data-quantity="{{ $order->ticket_quantity }}"
      data-status="{{ $order->status }}"
      data-tickets='@json(
        $order->tickets->map(fn($t) => [
          "type" => $t->ticketType->type,
          "quantity" => $t->quantity
        ])
      )'
    >
      <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>

      <td>
        {{ \Carbon\Carbon::parse($order->schedule->start_time)->format('Y/m/d H:i') }}<br>
        <span class="sub-text">{{ $order->schedule->venue }}</span>
      </td>

      <td>{{ $order->name }}</td>
      <td>{{ $order->email }}</td>
      <td class="center">{{ $order->ticket_quantity }}</td>

      {{-- 券種内訳 --}}
      <td>
        @foreach ($order->tickets as $ticket)
          <div>{{ $ticket->ticketType->type }} × {{ $ticket->quantity }}</div>
        @endforeach
      </td>

      {{-- 🔥 状態 --}}
      <td>
        @if($order->status === 'purchased')
          <span class="status-label status-purchased">
            予約済
          </span>
        @elseif($order->status === 'waiting_payment')
          <span class="status-label">
            振込待ち
          </span>
        @else
          <span class="status-label status-cancelled">
            キャンセル済
          </span>
        @endif
      </td>

      {{-- 操作 --}}
      <td>
        <button type="button" class="detail-button">
          詳細
        </button>

        <a href="{{ route('admin.reservations.edit', $order) }}"
           class="edit-link">
          編集
        </a>

        @if($order->status !== 'cancelled')
          <button type="button"
            class="cancel-button"
            data-id="{{ $order->id }}">
            キャンセル
          </button>
        @endif
      </td>

    </tr>
  @endforeach
</tbody>
    </table>
  @endif

  <div class="back-link-area">
    <a href="{{ route('admin.events.show', $event) }}">← 公演管理画面へ戻る</a>
  </div>

</div>


{{-- =========================
  予約詳細モーダル
========================= --}}
<div id="reservation-modal" class="modal hidden">
  <div class="modal-content">
    <h3>予約詳細</h3>

    <p>予約者：<span id="modal-name"></span></p>
    <p>メール：<span id="modal-email"></span></p>
    <p>予約日時：<span id="modal-created"></span></p>

    <hr>

    <p>公演日時：<span id="modal-schedule"></span></p>
    <p>会場：<span id="modal-venue"></span></p>

    <hr>

    <ul id="modal-tickets"></ul>

    <p>合計枚数：<span id="modal-quantity"></span></p>
    <p>ステータス：<span id="modal-status"></span></p>

    <div class="modal-actions">
      <button id="close-modal" class="btn-cancel">閉じる</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

  const modal = document.getElementById('reservation-modal');

  /* =========================
     詳細ボタン
  ========================= */
  document.querySelectorAll('.detail-button').forEach(button => {
    button.addEventListener('click', (e) => {

      const row = e.target.closest('.reservation-row');

      document.getElementById('modal-name').innerText = row.dataset.name;
      document.getElementById('modal-email').innerText = row.dataset.email;
      document.getElementById('modal-created').innerText = row.dataset.created;
      document.getElementById('modal-schedule').innerText = row.dataset.schedule;
      document.getElementById('modal-venue').innerText = row.dataset.venue;
      document.getElementById('modal-quantity').innerText = row.dataset.quantity;

      document.getElementById('modal-status').innerText =
        row.dataset.status === 'cancelled' ? 'キャンセル済' : '予約済';

      const tickets = JSON.parse(row.dataset.tickets);
      const list = document.getElementById('modal-tickets');
      list.innerHTML = '';

      tickets.forEach(t => {
        const li = document.createElement('li');
        li.innerText = `${t.type} × ${t.quantity}`;
        list.appendChild(li);
      });

      modal.classList.remove('hidden');
    });
  });

  document.getElementById('close-modal').addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  /* =========================
     🔥 キャンセルボタン
  ========================= */
  document.querySelectorAll('.cancel-button').forEach(button => {
    button.addEventListener('click', () => {

      if (!confirm('本当にキャンセルしますか？')) return;

      const orderId = button.dataset.id;

      fetch(`/admin/reservations/${orderId}/cancel`, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(() => {
        location.reload();
      });

    });
  });

});
</script>

@endsection