@extends('layouts.app', ['title' => $event->title])

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-8">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                        <p class="text-gray-600">Created by {{ $event->creator->name }}</p>
                    </div>

                    @if($event->description)
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">Description</h2>
                            <p class="text-gray-600">{{ $event->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Location</p>
                                    <p class="font-semibold">{{ $event->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Date & Time</p>
                                    <p class="font-semibold">{{ $event->event_date_time->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Available Seats</p>
                                    <p class="font-semibold">{{ $event->available_seats }} / {{ $event->total_seats }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="font-semibold">
                                        @if($event->available_seats > 0)
                                            <span class="text-green-600">Available</span>
                                        @else
                                            <span class="text-red-600">Fully Booked</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if($event->available_seats > 0)
                            <form method="POST" action="{{ route('bookings.store') }}" class="bg-gray-50 p-6 rounded-lg">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Book This Event</h3>
                                
                                <div class="flex items-end space-x-4">
                                    <div class="flex-1">
                                        <label for="number_of_seats" class="block text-sm font-medium text-gray-700 mb-2">
                                            Number of Seats
                                        </label>
                                        <input type="number" id="number_of_seats" name="number_of_seats" 
                                               min="1" max="{{ $event->available_seats }}" value="1" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <button type="submit" 
                                            class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                                        Book Now
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="bg-red-50 border border-red-200 p-6 rounded-lg">
                                <p class="text-red-700 font-semibold">This event is fully booked.</p>
                            </div>
                        @endif
                    @else
                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                            <p class="text-blue-700">
                                <a href="{{ route('login') }}" class="font-semibold hover:underline">Login</a> or 
                                <a href="{{ route('register') }}" class="font-semibold hover:underline">register</a> to book this event.
                            </p>
                        </div>
                    @endauth

                    @if(Auth::check() && Auth::id() === $event->created_by)
                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('events.edit', $event) }}" 
                               class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">
                                Edit Event
                            </a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                                    Delete Event
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
