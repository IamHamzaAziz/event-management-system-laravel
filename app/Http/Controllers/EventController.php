<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\Access\AuthorizationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Event::with('creator');

            // Filter by location
            if ($request->filled('location')) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            // Filter by date
            if ($request->filled('date')) {
                $query->whereDate('event_date_time', $request->date);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('event_date_time', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('event_date_time', '<=', $request->date_to);
            }

            $events = $query->orderBy('event_date_time', 'asc')
                ->paginate(10)
                ->withQueryString();
            
            return view('events.index', compact('events'));
        } catch (Exception $e) {
            Log::error('Error in EventController@index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('events.create');
        } catch (Exception $e) {
            Log::error('Error in EventController@create: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'event_date_time' => 'required|date|after:now',
                'total_seats' => 'required|integer|min:1',
            ]);

            $event = Event::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'event_date_time' => $validated['event_date_time'],
                'total_seats' => $validated['total_seats'],
                'available_seats' => $validated['total_seats'],
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('events.show', $event)
                ->with('success', 'Event created successfully!');
        } catch (Exception $e) {
            Log::error('Error in EventController@store: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        try {
            $event->load(['creator', 'bookings.user']);
            
            return view('events.show', compact('event'));
        } catch (Exception $e) {
            Log::error('Error in EventController@show: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        try {
            $this->authorize('update', $event);
            
            return view('events.edit', compact('event'));
        } catch (Exception $e) {
            Log::error('Error in EventController@edit: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $this->authorize('update', $event);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'event_date_time' => 'required|date|after:now',
                'total_seats' => 'required|integer|min:' . ($event->total_seats - $event->available_seats),
            ]);

            \DB::beginTransaction();
            
            $event->update($validated);
            
            // Update available seats if total seats changed
            $event->updateAvailableSeats();
            
            \DB::commit();

            return redirect()->route('events.show', $event)
                ->with('success', 'Event updated successfully!');
        } catch (Exception $e) {
            Log::error('Error in EventController@update: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            $this->authorize('delete', $event);
            
            \DB::beginTransaction();
            
            $event->delete();
            
            \DB::commit();

            return redirect()->route('my-events')
                ->with('success', 'Event deleted successfully!');
        } catch (Exception $e) {
            Log::error('Error in EventController@destroy: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
}
