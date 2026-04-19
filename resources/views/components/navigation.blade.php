@php
    $currentPage = request()->route()->getName();
@endphp

<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">Event Booking</a>
            </div>
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 {{ $currentPage === 'login' ? 'font-semibold text-gray-900' : '' }}">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 {{ $currentPage === 'register' ? 'font-semibold text-gray-900' : '' }}">Register</a>
                @else
                    <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-900 {{ $currentPage === 'events.index' ? 'font-semibold text-gray-900' : '' }}">Events</a>
                    <a href="{{ route('my-events') }}" class="text-gray-600 hover:text-gray-900 {{ $currentPage === 'my-events' ? 'font-semibold text-gray-900' : '' }}">My Events</a>
                    <a href="{{ route('bookings.index') }}" class="text-gray-600 hover:text-gray-900 {{ $currentPage === 'bookings.index' ? 'font-semibold text-gray-900' : '' }}">My Bookings</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-900">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>
