@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-event-reservation.css') }}">
@endsection

@section('content')
<div class="event-manage-container">
  <h1 class="page-title">
    {{ $event->title }}｜予約フォーム作成
  </h1>

<div class="public-url">

  <div class="public-url-label">
    公開URL
  </div>

  <div class="public-url-box">
    <input
      type="text"
      id="reservation-url"
      class="public-url-input"
      value="{{ config('services.reservation.base_url') }}{{ $event->reservation_form_url }}"
      readonly
    >

    <button
      type="button"
      class="copy-button"
      onclick="copyReservationUrl()"
    >
      URLをコピー
    </button>
  </div>

  <p class="url-note">
    ※ このURLをお客様に案内してください。
  </p>

</div>

  <form method="POST"
      action="{{ route('admin.events.reservation.store', $event) }}"
      enctype="multipart/form-data">
    @csrf

    {{-- 予約対象 --}}
    <div class="setting-block">
      <div class="setting-label">予約対象</div>
      <div class="setting-content">
        <p>一般向け</p>
      </div>
    </div>

    {{-- 予約方法 --}}
    <div class="setting-block">
      <div class="setting-label">予約方法</div>
      <div class="setting-content">
        <p>一般予約</p>
      </div>
    </div>

{{-- 予約開始 --}}
<div class="setting-block">
  <div class="setting-label">予約開始</div>
  <div class="setting-content">
    <input type="date" name="reservation_start_date" class="date-input">
    <select name="reservation_start_time" class="time-select">
  @for ($h = 0; $h < 24; $h++)
    <option value="{{ sprintf('%02d:00', $h) }}">
      {{ sprintf('%02d:00', $h) }}
    </option>
    <option value="{{ sprintf('%02d:59', $h) }}">
      {{ sprintf('%02d:59', $h) }}
    </option>
  @endfor
</select>
    <p class="help-text">※ 指定した日時から予約受付を開始します。</p>
  </div>

</div>
    {{-- 予約停止条件 --}}
    <div class="setting-block">
      <div class="setting-label">予約停止</div>
      <div class="setting-content">
        残り
        <input type="number" class="small-input"> 枚
        <p class="help-text">
          設定した残数になると予約受付を停止します。
        </p>
      </div>
    </div>

   {{-- 予約終了 --}}
<div class="setting-block">
  <div class="setting-label">予約終了</div>
  <div class="setting-content">
    <label class="radio-item">
      <input type="radio" name="reservation_end_type" value="event_end" checked>
      公演終了まで
    </label><br>

    <label class="radio-item">
      <input type="radio" name="reservation_end_type" value="datetime">
      日時指定
    </label>

    <div class="datetime-group" id="reservationEndDatetime">
      <input type="date" name="reservation_end_date" class="date-input">
      <select name="reservation_end_time" class="time-select">
  @for ($h = 0; $h < 24; $h++)
    <option value="{{ sprintf('%02d:00', $h) }}">
      {{ sprintf('%02d:00', $h) }}
    </option>
    <option value="{{ sprintf('%02d:59', $h) }}">
      {{ sprintf('%02d:59', $h) }}
    </option>
  @endfor
</select>
    </div>

    <p class="help-text">
      ※ 通常は「公演終了まで」を選択してください。
    </p>
  </div>
</div>

{{-- 支払方法 --}}
<div class="setting-block">
  <div class="setting-label">支払方法</div>
  <div class="setting-content">
    <label>
      <input type="checkbox"
             name="allow_cash"
             value="1"
             {{ $event->allow_cash ? 'checked' : '' }}>
      当日精算
    </label><br>

    <label>
      <input type="checkbox"
             name="allow_transfer"
             value="1"
             {{ $event->allow_transfer ? 'checked' : '' }}>
      振込
    </label>
  </div>
</div>

{{-- 予約可能な券種 --}}
<div class="setting-block">
  <div class="setting-label">予約可能な券種</div>
  <div class="setting-content">
    <p class="help-text">
      この予約フォームから予約可能にする券種にチェックを付けて下さい。
    </p>

   @forelse ($ticketTypes ?? [] as $ticketType)
  <label class="checkbox-item">
    <input type="checkbox" checked>
    {{ $ticketType->type }}（¥{{ number_format($ticketType->price) }}）
  </label><br>
@empty
  <p class="help-text">※ まだ券種が登録されていません。</p>
@endforelse
  </div>
</div>
{{-- チラシ画像 --}}
<div class="setting-block">
  <div class="setting-label">チラシ画像（A4縦・2枚まで）</div>
  <div class="setting-content">

    <div class="flyer-upload-wrapper">

      {{-- 画像1 --}}
      <div class="flyer-box">
        <label class="upload-btn">
          画像1をアップロード
          <input type="file"
                 id="imageInput1"
                 name="reservation_image_1"
                 accept="image/*"
                 hidden>
        </label>

        <div class="flyer-preview">
          <img id="preview1"
               src="{{ !empty($event->reservation_image_1) ? asset('storage/' . $event->reservation_image_1) : '' }}"
               style="{{ empty($event->reservation_image_1) ? 'display:none;' : '' }}">
        </div>

        @if(!empty($event->reservation_image_1))
          <label class="delete-check">
            <input type="checkbox" name="delete_reservation_image_1" value="1">
            画像1を削除する
          </label>
        @endif
      </div>

      {{-- 画像2 --}}
      <div class="flyer-box">
        <label class="upload-btn">
          画像2をアップロード
          <input type="file"
                 id="imageInput2"
                 name="reservation_image_2"
                 accept="image/*"
                 hidden>
        </label>

        <div class="flyer-preview">
          <img id="preview2"
               src="{{ !empty($event->reservation_image_2) ? asset('storage/' . $event->reservation_image_2) : '' }}"
               style="{{ empty($event->reservation_image_2) ? 'display:none;' : '' }}">
        </div>

        @if(!empty($event->reservation_image_2))
          <label class="delete-check">
            <input type="checkbox" name="delete_reservation_image_2" value="1">
            画像2を削除する
          </label>
        @endif
      </div>

    </div>

  </div>
</div> 
{{-- =========================
   フォーム送信エリア
========================= --}}
<div class="form-footer">
  <button type="submit" class="save-btn">登録する</button>

  <div class="back-link-wrapper">
    <a href="{{ route('admin.events.show', $event) }}" class="back-link">
      ← 公演管理画面へ戻る
    </a>
  </div>
</div>

</form>

<script>
document.addEventListener('DOMContentLoaded', () => {

  // 予約終了表示制御
  const radios = document.querySelectorAll('input[name="reservation_end_type"]');
  const datetimeGroup = document.getElementById('reservationEndDatetime');

  const toggleDatetime = () => {
    const checked = document.querySelector('input[name="reservation_end_type"]:checked');
    datetimeGroup.style.display = checked.value === 'datetime' ? 'block' : 'none';
  };

  radios.forEach(radio => {
    radio.addEventListener('change', toggleDatetime);
  });

  toggleDatetime();


  // URLコピー
  window.copyReservationUrl = function() {
    const text = document.getElementById('reservation-url').value;

    navigator.clipboard.writeText(text).then(() => {
      alert('URLをコピーしました');
    }).catch(() => {
      alert('コピーに失敗しました');
    });
  };


  // 画像プレビュー
  function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (!input || !preview) return;

    input.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;

      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };

      reader.readAsDataURL(file);
    });
  }

  previewImage('imageInput1', 'preview1');
  previewImage('imageInput2', 'preview2');

});
</script>
@endsection
