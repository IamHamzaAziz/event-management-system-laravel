@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Your Dashboard!</h1>
                <p class="text-gray-600 mb-6">Hello, {{ Auth::user()->name }}! Here's what you can do:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Browse Events</h3>
                        <p class="text-gray-600 mb-4">View and book available events</p>
                        <a href="{{ route('events.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            View Events
                        </a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">My Bookings</h3>
                        <p class="text-gray-600 mb-4">Manage your event bookings</p>
                        <a href="{{ route('bookings.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                            View Bookings
                        </a>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Create Event</h3>
                        <p class="text-gray-600 mb-4">Organize your own event</p>
                        <a href="{{ route('events.create') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">
                            Create Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
