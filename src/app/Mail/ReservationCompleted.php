<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class ReservationCompleted extends Mailable
{
  use SerializesModels;

  public function __construct(
    public Order $order
  ) {}

  public function build()
  {
    return $this
      ->subject('【予約完了】ご予約ありがとうございます')
      ->view('emails.reservation-completed');
  }
}