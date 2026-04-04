<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
  protected $fillable = [
    'event_id',
    'start_time',
    'venue',
    'seat_limit',
    'sale_start',
    'sale_end',
    'seat_capacity',
  ];

  protected $casts = [
    'start_time' => 'datetime',
  ];

  public function event()
  {
    return $this->belongsTo(Event::class);
  }

  public function ticketTypes()
  {
    return $this->hasMany(TicketType::class);
  }
}
