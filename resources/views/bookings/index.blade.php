@extends('layouts.app', ['title' => 'My Bookings'])

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
                <p class="text-gray-600">Manage your event bookings</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <a href="{{ route('events.show', $booking->event) }}" class="hover:text-blue-600 transition">
                                    {{ $booking->event->title }}
                                </a>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $booking->event->location }}
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $booking->event->event_date_time->format('M j, Y g:i A') }}
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $booking->number_of_seats }} seat{{ $booking->number_of_seats > 1 ? 's' : '' }}
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Booked on {{ $booking->booking_date->format('M j, Y') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="ml-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($booking->booking_status === 'booked')
                                    bg-green-100 text-green-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($booking->booking_status === 'booked')
                        <div class="mt-4 flex justify-end">
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" 
                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition text-sm">
                                    Cancel Booking
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                    <p class="text-gray-500 mb-4">You haven't booked any events yet.</p>
                    <a href="{{ route('events.index') }}" 
                       class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                        Browse Events
                    </a>
                </div>
            @endforelse

            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
