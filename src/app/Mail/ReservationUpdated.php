<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationUpdated extends Mailable
{
  use SerializesModels;

  public function __construct(
    public Order $order
  ) {}

  public function build()
  {
    return $this
      ->subject('【予約変更】ご予約内容が更新されました')
      ->view('emails.reservation-updated');
  }
}
