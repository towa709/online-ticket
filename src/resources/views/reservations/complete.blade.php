@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation-complete.css') }}">
@endsection

@section('content')
<div class="reservation-complete-container">

  <h1 class="complete-title">
    ご予約ありがとうございました
  </h1>

  <p class="complete-message">
    {{ $event->title }} のご予約を受け付けました。
  </p>

  <p class="complete-sub-message">
    ご入力いただいたメールアドレス宛に、<br>
    予約内容の確認メールをお送りしています。
  </p>

  {{-- フッター --}}
  <div class="complete-footer">
<a
  href="{{ route('reservations.create', $event) }}"
  class="continue-link"
>
  予約を続ける
</a>
  </div>

</div>
@endsection
