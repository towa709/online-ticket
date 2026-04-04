@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
@endsection

@section('content')
<div class="login-container">
  <h1 class="login-title">管理者ログイン</h1>
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
      <label for="email">メールアドレス</label>
      <input
        type="email"
        id="email"
        name="email"
        value="{{ old('email') }}"
      >
      @error('email')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <div class="form-group">
      <label for="password">パスワード</label>
      <input
        type="password"
        id="password"
        name="password"
      >
      @error('password')
        <p class="error">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="btn-login">
      ログインする
    </button>

@if ($errors->has('login'))
  <p class="login-error">
    {{ $errors->first('login') }}
  </p>
@endif

    <p class="switch-link">
      <a href="{{ route('register') }}">管理者登録はこちら</a>
    </p>
  </form>
</div>
@endsection
