<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCancelled extends Mailable
{
  use SerializesModels;

  public function __construct(
    public Order $order
  ) {}

  public function build()
  {
    return $this
      ->subject('【予約キャンセル】ご予約がキャンセルされました')
      ->view('emails.reservation-cancelled');
  }
}
