# Event Booking System

A comprehensive event management and booking application built with Laravel. This system allows users to create events, manage bookings, and handle seat availability in real-time.

## Features

- **User Authentication**: Secure registration and login system
- **Event Management**: Create, view, edit, and delete events
- **Event Booking**: Book seats for available events
- **Seat Management**: Real-time seat availability tracking
- **User Dashboard**: Personalized dashboard with quick actions
- **My Events**: Manage events you've created
- **My Bookings**: View and manage your event bookings
- **Booking Cancellation**: Cancel bookings with automatic seat refund
- **Email Notifications**: Booking confirmation emails
- **Responsive Design**: Mobile-friendly interface using Tailwind CSS

## Project Description

The Event Booking System is a web application that facilitates event creation and seat booking management. Users can register as event organizers or attendees, create events with specified seat capacities, and book available seats. The system handles real-time seat availability updates, prevents double bookings, and provides a clean, intuitive interface for managing events and bookings.

## Project Structure
Since this is a simple application, I wrote the code in Controllers entirely rather than going for Service (for business logic) Repository (for database interaction) pattern. Also I used blade templates rather than going for a framework like Next.js.

## Test Credentials
Following are the test credentials for this application (make sure to use an existing email in order to receive mails and test the mail functionality implemented on booking)
- **Email**: user@example.com
- **Password**: password (keeping it simple and not applying any validations due to nature of project)


## Installation Instructions

### Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL or PostgreSQL database
- Node.js and NPM (for asset compilation)

### Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd event_booking_system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure database**
   Edit the `.env` file and update your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=event_booking
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Compile assets**
   ```bash
   npm run dev
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Environment Setup

### Configuration Files

1. **`.env` file** - Contains environment-specific settings:
   - Database connection details
   - Mail configuration for email notifications
   - Application URL and debugging settings

2. **`config/app.php`** - Application configuration
3. **`config/database.php`** - Database configuration
4. **`config/mail.php`** - Email configuration

### Required Environment Variables

```env
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=true
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

## Database Migration Steps

### Migration Files

The application includes the following migration files:

1. **`create_users_table`** - User authentication data
2. **`create_events_table`** - Event information and seat management
3. **`create_bookings_table`** - Booking records and seat allocations

### Running Migrations
Execute the following command in terminal
   ```bash
   php artisan migrate
   ```

### Migration Details

- **Events Table**: Stores event details including title, description, location, date/time, and seat capacity
- **Bookings Table**: Tracks user bookings with seat counts and booking status
- **Foreign Keys**: Proper relationships between users, events, and bookings
- **Indexes**: Optimized for performance on frequently queried columns

## Seeder Usage

### Available Seeders

1. **`DatabaseSeeder`** - Main seeder class
2. **`UserSeeder`** - Creates sample users (optional)
3. **`EventSeeder`** - Creates sample events (optional)

### Running Seeders

Run all seeders:
   ```bash
   php artisan db:seed
   ```

## Application Flow

### User Interaction Flow

1. **Registration/Login**
   - New users register with email and password
   - Existing users login to access the system
   - Dashboard is displayed after successful login

2. **Event Discovery**
   - Browse all available events on the events page
   - Filter events by location or date range
   - View detailed event information including seat availability

3. **Event Booking**
   - Select desired number of seats for an event
   - System validates seat availability
   - Booking is confirmed and seats are deducted from availability
   - Email notification is sent to the user

4. **Booking Management**
   - View all personal bookings on "My Bookings" page
   - Cancel bookings if needed (seats are returned to availability)
   - Track booking status and history

5. **Event Management (Organizers)**
   - Create new events with specified seat capacity
   - Edit event details (title, description, date, location)
   - View bookings for created events
   - Delete events (if no bookings exist)

### How Event Booking Works

1. **Event Creation**
   - Organizers create events with total seat capacity
   - Available seats initially equal total seats
   - Events are listed on the events page for users to discover

2. **Seat Availability**
   - Each booking reduces available seats by the booked amount
   - Real-time validation prevents overbooking
   - Cancelled bookings return seats to available pool
   - System ensures available seats never go negative

3. **Booking Process**
   - User selects event and desired seat quantity
   - System checks if enough seats are available
   - If available, booking is created and seats are reserved
   - Booking confirmation email is sent
   - Available seats are immediately updated

### How Seat Availability is Handled

1. **Initial Setup**
   - When an event is created: `available_seats = total_seats`
   - Seat counts are stored in the events table

2. **Booking Validation**
   - Before booking: Check if `available_seats >= requested_seats`
   - If insufficient seats: Show error message
   - If sufficient: Proceed with booking creation

3. **Seat Updates**
   - Successful booking: `available_seats -= booked_seats`
   - Booking cancellation: `available_seats += cancelled_seats`
   - Event edit: Adjust available seats if total capacity changes

4. **Concurrency Protection**
   - Database transactions ensure atomic operations
   - Prevents race conditions during simultaneous bookings
   - Maintains data integrity under high load

5. **Real-time Display**
   - Available seats are displayed on event pages
   - Updates immediately after bookings or cancellations
   - "Sold out" status when `available_seats = 0`


