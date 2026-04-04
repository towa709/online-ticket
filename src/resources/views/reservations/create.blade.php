@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation-form.css') }}">
@endsection

@section('content')
<div class="reservation-page">

  {{-- 公開予約フォーム --}}
  @include('admin.events.reservation.form', [
    'event' => $event,
    'ticketTypes' => $ticketTypes
  ])

</div>
@endsection
