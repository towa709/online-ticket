@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-event-create.css') }}">
@endsection

@section('content')
<div class="event-create-wrapper">

  {{-- タイトル（カード外） --}}
  <h1 class="page-title">新規公演登録</h1>

  <div class="event-create-container">

    @if ($errors->any())
      <div class="error-box">
        <ul class="error-list">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.events.store') }}" class="event-form">
      @csrf

      <div class="form-group">
        <label>公演名</label>
        <input type="text" name="title" value="{{ old('title') }}">
      </div>

      <div class="form-group">
        <label>劇団名</label>
        <input type="text" name="organization_name" value="{{ old('organization_name') }}">
      </div>

      <div class="form-group">
        <label>予約フォームURL</label>

        <div class="url-input-group">
          <span class="url-base">
            {{ config('services.reservation.base_url') }}
          </span>

          <input
            type="text"
            name="reservation_form_url"
            id="reservation_suffix"
            placeholder="自由に入力"
            value="{{ old('reservation_form_url') }}"
          >
        </div>

        <div class="url-buttons">
          <button type="button" id="manualBtn" class="url-btn active">
            自分で設定する
          </button>
          <button type="button" id="autoBtn" class="url-btn">
            自動で設定する
          </button>
        </div>
      </div>

      <button type="submit" class="submit-btn">登録する</button>
    </form>
  </div>

  {{-- 戻るリンク（カード外） --}}
  <div class="below-link">
    <a href="{{ route('admin.index') }}">
      ← トップへ戻る
    </a>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const autoBtn = document.getElementById('autoBtn');
  const manualBtn = document.getElementById('manualBtn');
  const input = document.getElementById('reservation_suffix');

  autoBtn.addEventListener('click', () => {
    const random = Math.random().toString(36).substring(2, 10);
    const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    input.value = `event-${date}-${random}`;
    autoBtn.classList.add('active');
    manualBtn.classList.remove('active');
  });

  manualBtn.addEventListener('click', () => {
    input.value = '';
    manualBtn.classList.add('active');
    autoBtn.classList.remove('active');
  });
});
</script>
@endsection
