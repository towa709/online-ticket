<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
  protected $fillable = [
    'event_schedule_id',
    'type',
    'price',
    'stock',
  ];

  public function eventSchedule()
  {
    return $this->belongsTo(EventSchedule::class);
  }
}
