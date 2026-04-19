<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'event_date_time',
        'total_seats',
        'available_seats',
        'created_by',
    ];

    protected $casts = [
        'event_date_time' => 'datetime',
        'booking_date' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateAvailableSeats(): void
    {
        $bookedSeats = $this->bookings()
            ->where('booking_status', 'booked')
            ->sum('number_of_seats');
        
        $this->available_seats = $this->total_seats - $bookedSeats;
        $this->save();
    }
}
