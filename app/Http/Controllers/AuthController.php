<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        try {
            return view('auth.login');
        } catch (Exception $e) {
            Log::error('Error in AuthController@showLoginForm: ' . $e->getMessage());
            return redirect('/')->with('error', 'An error occurred. Please try again.');
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard'));
            }

            return back()->withErrors([
                'email' => __('auth.failed'),
            ]);
        } catch (Exception $e) {
            Log::error('Error in AuthController@login: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.')->withInput();
        }
    }

    public function showRegistrationForm()
    {
        try {
            return view('auth.register');
        } catch (Exception $e) {
            Log::error('Error in AuthController@showRegistrationForm: ' . $e->getMessage());
            return redirect('/')->with('error', 'An error occurred. Please try again.');
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Log::error('Error in AuthController@register: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.')->withInput();
        }
    }

    public function dashboard()
    {
        try {
            return view('dashboard');
        } catch (Exception $e) {
            Log::error('Error in AuthController@dashboard: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function myEvents()
    {
        try {
            $user = Auth::user();
            
            // Get user's created events with bookings
            $events = Event::where('created_by', $user->id)
                ->with(['bookings' => function($query) {
                    $query->with('user')->orderBy('created_at', 'desc');
                }])
                ->orderBy('event_date_time', 'asc')
                ->paginate(10);
            
            return view('my-events', compact('events'));
        } catch (Exception $e) {
            Log::error('Error in AuthController@myEvents: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (Exception $e) {
            Log::error('Error in AuthController@logout: ' . $e->getMessage());
            return redirect('/')->with('error', 'An error occurred. Please try again.');
        }
    }
}
