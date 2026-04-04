@extends('layouts.app')

@section('content')
<div class="reservation-complete-container">

  <h1 class="page-title">
    ご予約ありがとうございました
  </h1>

  <p class="complete-message">
    {{ $event->title }} のご予約が完了しました。
  </p>

  <p class="complete-note">
    ご入力いただいたメールアドレス宛に、<br>
    予約内容の確認メールをお送りしています。
  </p>

  <div class="complete-actions">
    <a href="{{ url('/') }}" class="btn-primary">
      トップページへ戻る
    </a>
  </div>

</div>
@endsection
