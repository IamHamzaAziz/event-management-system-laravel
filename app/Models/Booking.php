<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'number_of_seats',
        'booking_status',
        'booking_date',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function isBooked(): bool
    {
        return $this->booking_status === 'booked';
    }

    public function isCancelled(): bool
    {
        return $this->booking_status === 'cancelled';
    }

    public function cancel(): void
    {
        $this->booking_status = 'cancelled';
        $this->save();
        
        // Update available seats in the event
        $this->event->updateAvailableSeats();
    }
}
