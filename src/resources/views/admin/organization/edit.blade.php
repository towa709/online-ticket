@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-organization.css') }}">
@endsection

@section('content')
<div class="organization-wrapper">

  {{-- タイトル（コンテナ外） --}}
  <h1 class="organization-title">劇団情報管理</h1>

  <div class="organization-container">
    <form method="POST" action="{{ route('admin.organization.update') }}" class="organization-form">
      @csrf

      <div class="form-group">
        <label>劇団名</label>
        <input type="text" name="name" value="{{ old('name', $organization->name) }}">
      </div>

      <div class="form-group">
        <label>予約メールアドレス</label>
        <input
          type="email"
          name="reservation_email"
          value="{{ old('reservation_email', $organization->reservation_email) }}"
        >
        @error('reservation_email')
          <p class="error">{{ $message }}</p>
        @enderror
      </div>

      <button class="btn-save">保存する</button>
    </form>
  </div>

  {{-- 戻るリンク（カード外） --}}
  <div class="below-link">
    <a href="{{ route('admin.index') }}">
      ← トップへ戻る
    </a>
  </div>

</div>
@endsection
