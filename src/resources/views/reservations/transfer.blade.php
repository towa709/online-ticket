@extends('layouts.app')

@section('content')
<div class="transfer-container">
  <h1>お振込のご案内</h1>

  <p>
    下記口座へお振込をお願いいたします。
  </p>

  <div class="transfer-info">
    <p>銀行名：◯◯銀行</p>
    <p>支店名：◯◯支店</p>
    <p>口座番号：1234567</p>
    <p>名義：◯◯劇団</p>
  </div>

  @if($order->transfer_due_at)
  <p>
振込期限：
{{ optional($order->transfer_due_at)->format('Y/m/d H:i') }}
</p>
@endif

<p>
ご入金確認後、予約確定メールをお送りします。
</p>

<a href="{{ route('reservations.create', $event) }}">
  予約を続ける
</a>
</div>
@endsection