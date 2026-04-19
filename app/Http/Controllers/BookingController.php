<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Notifications\BookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class BookingController extends Controller
{
    public function index()
    {
        try {
            $bookings = Booking::with(['event', 'user'])
                ->where('user_id', Auth::id())
                ->orderBy('booking_date', 'desc')
                ->paginate(10);

            return view('bookings.index', compact('bookings'));
        } catch (Exception $e) {
            Log::error('Error in BookingController@index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'number_of_seats' => 'required|integer|min:1',
            ]);

            $event = Event::findOrFail($validated['event_id']);

            // Check if enough seats are available
            if ($event->available_seats < $validated['number_of_seats']) {
                return back()->withErrors([
                    'number_of_seats' => 'Not enough seats available. Only ' . $event->available_seats . ' seats left.'
                ])->withInput();
            }

            // Check if user already has a booking for this event
            $existingBooking = Booking::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->where('booking_status', 'booked')
                ->first();

            if ($existingBooking) {
                return back()->withErrors([
                    'event_id' => 'You already have a booking for this event.'
                ])->withInput();
            }

            // Create the booking with database transaction
            \DB::beginTransaction();
            
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'number_of_seats' => $validated['number_of_seats'],
                'booking_status' => 'booked',
                'booking_date' => now(),
            ]);

            // Update available seats
            $event->updateAvailableSeats();
            
            \DB::commit();

            // Send email notification (non-blocking)
            try {
                Auth::user()->notify(new BookingConfirmed($booking));
            } catch (Exception $e) {
                Log::warning('Email notification failed for booking ' . $booking->id . ': ' . $e->getMessage());
            }

            return redirect()->route('bookings.index')
                ->with('success', 'Booking created successfully!');
                
        } catch (Exception $e) {
            Log::error('Error in BookingController@store: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.')->withInput();
        }
    }

    public function cancel(Booking $booking)
    {
        try {
            // Ensure user can only cancel their own bookings
            if ($booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if ($booking->booking_status === 'cancelled') {
                return back()->withErrors(['booking' => 'This booking is already cancelled.']);
            }

            // Use database transaction for cancellation
            \DB::beginTransaction();
            
            $booking->cancel();
            
            \DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking cancelled successfully!');
                
        } catch (Exception $e) {
            Log::error('Error in BookingController@cancel: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
}
