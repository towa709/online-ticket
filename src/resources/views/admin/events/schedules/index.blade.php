@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-schedule.css') }}">
@endsection

@section('content')
<div class="schedule-container">

  <h1 class="page-title">
    {{ $event->title }}｜公演日程管理
  </h1>

  {{-- 日程一覧 --}}
  <section class="schedule-section">
    <h2 class="section-title">公演日程一覧</h2>

    <table class="schedule-table">
      <thead>
        <tr>
          <th>日時</th>
          <th>会場</th>
          <th>座席数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($schedules as $schedule)
          <tr>
            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('Y/m/d H:i') }}</td>
            <td>{{ $schedule->venue }}</td>
            <td>{{ $schedule->seat_limit }}</td>
            <td>
              <div class="action-buttons">
                <button
                  class="btn-edit"
                  data-id="{{ $schedule->id }}"
                  data-date="{{ \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d') }}"
                  data-hour="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H') }}"
                  data-minute="{{ \Carbon\Carbon::parse($schedule->start_time)->format('i') }}"
                  data-venue="{{ $schedule->venue }}"
                  data-seat="{{ $schedule->seat_limit }}"
                >
                  変更
                </button>

                <form
                  method="POST"
                  action="{{ route('admin.events.schedules.destroy', [$event, $schedule]) }}"
                  class="delete-form"
                >
                  @csrf
                  @method('DELETE')
                  <button class="btn-delete">削除</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="empty-text">
              まだ日程が登録されていません
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </section>

  {{-- 日程追加 --}}
  <section class="schedule-section">
    <h2 class="section-title">日程を追加</h2>

    <form method="POST"
          action="{{ route('admin.events.schedules.store', $event) }}"
          class="schedule-form">
      @csrf

      <div class="form-row">
        <div class="form-group">
          <label>日付</label>
          <input type="date" name="date" required>
        </div>

        <div class="form-group time-group">
          <label>開演時間</label>
          <div class="time-inputs">
            <select name="hour" required>
              <option value="">--</option>
              @for ($h = 0; $h <= 23; $h++)
                <option value="{{ $h }}">{{ sprintf('%02d', $h) }}</option>
              @endfor
            </select>
            <span>：</span>
            <select name="minute" required>
              <option value="">--</option>
              @for ($m = 0; $m <= 55; $m += 5)
                <option value="{{ $m }}">{{ sprintf('%02d', $m) }}</option>
              @endfor
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>会場</label>
          <input type="text" name="venue" required>
        </div>

        <div class="form-group">
          <label>座席数</label>
          <input type="number" name="seat_limit" min="1" required>
        </div>

        <div class="form-group">
          <label>&nbsp;</label>
          <button class="btn-add" type="submit">追加</button>
        </div>
      </div>
    </form>
  </section>

  <div class="page-back-area">
    <a href="{{ route('admin.events.edit', $event) }}" class="page-back-link">
      ← 基本情報画面へ戻る
    </a>
  </div>
</div>

{{-- 編集モーダル --}}
<div id="edit-modal" class="modal hidden">
  <div class="modal-content">
    <h3>日程を変更</h3>

    <form id="edit-form" method="POST">
      @csrf
      @method('PUT')

      <div class="edit-group">
        <label>日付</label>
        <input type="date" name="date" required>
      </div>

      <div class="edit-group">
        <label>開演時間</label>
        <div class="time-inputs">
          <select name="hour">
            @for ($h = 0; $h <= 23; $h++)
              <option value="{{ $h }}">{{ sprintf('%02d',$h) }}</option>
            @endfor
          </select>
          <span>：</span>
          <select name="minute">
            @for ($m = 0; $m <= 55; $m += 5)
              <option value="{{ $m }}">{{ sprintf('%02d',$m) }}</option>
            @endfor
          </select>
        </div>
      </div>

      <div class="edit-group">
        <label>会場</label>
        <input type="text" name="venue">
      </div>

      <div class="edit-group">
        <label>座席数</label>
        <input type="number" name="seat_limit" min="1">
      </div>

      <div class="modal-actions">
        <button type="button" id="cancel-edit" class="btn-cancel">キャンセル</button>
        <button type="submit" class="btn-add">変更する</button>
      </div>
    </form>
  </div>
</div>

{{-- 削除確認モーダル --}}
<div id="delete-modal" class="modal hidden">
  <div class="modal-content">
    <p>この日程を削除しますか？</p>
    <div class="modal-actions">
      <button id="cancel-delete" class="btn-cancel">キャンセル</button>
      <button id="confirm-delete" class="btn-delete">削除する</button>
    </div>
  </div>
</div>

{{-- JS（削除確認） --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('delete-modal');
  const cancelBtn = document.getElementById('cancel-delete');
  const confirmBtn = document.getElementById('confirm-delete');

  let targetForm = null;

  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      targetForm = form;
      modal.classList.remove('hidden');
    });
  });

  cancelBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    targetForm = null;
  });

  confirmBtn.addEventListener('click', () => {
    if (targetForm) targetForm.submit();
  });
});
</script>

{{-- JS（編集モーダル） --}}
<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', () => {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');

    form.action = `/admin/events/{{ $event->id }}/schedules/${btn.dataset.id}`;

    form.date.value = btn.dataset.date;
    form.hour.value = parseInt(btn.dataset.hour, 10);
    form.minute.value = parseInt(btn.dataset.minute, 10);
    form.venue.value = btn.dataset.venue;
    form.seat_limit.value = btn.dataset.seat;

    modal.classList.remove('hidden');
  });
});

document.getElementById('cancel-edit').addEventListener('click', () => {
  document.getElementById('edit-modal').classList.add('hidden');
});
</script>
@endsection
