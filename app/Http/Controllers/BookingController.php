<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingNotification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())->with('property')->get();
        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'meeting_time' => 'required|date_format:Y-m-d H:i:s|after:now',
            'message' => 'nullable|string'
        ]);

        $booking = new Booking($validatedData);
        $booking->user_id = $request->user()->id;
        $booking->save();

        // Notify agent
        $agent = $booking->property->user;
        $agent->notify(new BookingNotification($booking));

        return response()->json([
            'status' => true,
            'booking' => $booking
        ], 201);
    }

    // Implement show, update, destroy if necessary
}
