@extends('layouts.app', ['title' => 'Edit Event'])

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Event</h1>
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('events.update', $event) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Event Title *</label>
                            <input type="text" id="title" name="title" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('title', $event->title) }}">
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location *</label>
                            <input type="text" id="location" name="location" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('location', $event->location) }}">
                        </div>

                        <div class="mb-6">
                            <label for="event_date_time" class="block text-gray-700 text-sm font-bold mb-2">Event Date & Time *</label>
                            <input type="datetime-local" id="event_date_time" name="event_date_time" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('event_date_time', $event->event_date_time->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="mb-6">
                            <label for="total_seats" class="block text-gray-700 text-sm font-bold mb-2">Total Seats *</label>
                            <input type="number" id="total_seats" name="total_seats" min="{{ $event->total_seats - $event->available_seats }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('total_seats', $event->total_seats) }}">
                            <p class="text-sm text-gray-500 mt-1">Minimum: {{ $event->total_seats - $event->available_seats }} seats ({{ $event->total_seats - $event->available_seats }} already booked)</p>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('events.show', $event) }}" 
                               class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                                Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
