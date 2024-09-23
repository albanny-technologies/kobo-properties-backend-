<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Property;
use App\Notifications\BookingAccepted;
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
        $booking->agent_id = 0;
        $booking->save();

        // Notify agent
        $agent = $booking->property->user;
        $booking->agent_id = $agent->id;
        $booking->save();
        $agent->notify(new BookingNotification($booking));


        return response()->json([
            'status' => true,
            'booking' => $booking
        ], 201);
    }
    public function show(Booking $booking)
    {
        return response()->json([
            'status' => true,
            'booking' => $booking
        ]);
    }
    public function accept($id)
    {
        $user = auth()->user();

        // Retrieve the booking with the specified id and agent_id
        $booking = Booking::query()
            ->where('id', $id)  // Correctly match the booking by ID
            ->where('agent_id', $user->id)  // Ensure the booking belongs to the authenticated agent
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or not authorized to accept this booking'
            ], 404);
        }
        // Mark the booking as accepted (assuming you have a status field)
        $booking->status = BookingStatus::ACCEPTED;  // Adjust field name as per your schema
        $booking->save();

        $user->notify(new BookingAccepted($booking));


        return response()->json([
            'status' => true,
            'message' => 'Booking accepted successfully',
            'booking' => $booking
        ]);
    }

    public function reject($id)
    {
        $user = auth()->user();

        // Retrieve the booking with the specified id and agent_id
        $booking = Booking::query()
            ->where('id', $id)  // Correctly match the booking by ID
            ->where('agent_id', $user->id)  // Ensure the booking belongs to the authenticated agent
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or not authorized to reject this booking'
            ], 404);
        }
        $property = Property::query()->find($booking->property_id);
        if ($property->user->id == $user->id) {
            // Mark the booking as accepted (assuming you have a status field)
            $booking->status = BookingStatus::REJECTED;  // Adjust field name as per your schema
            $booking->save();

            return response()->json([
                'status' => true,
                'message' => 'Booking rejected successfully',
                'booking' => $booking
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You\'re not authorize to perform this action'
            ], 401);
        }
    }
    public function cancelled($id)
    {
        $user = auth()->user();

        // Retrieve the booking with the specified id and agent_id
        $booking = Booking::query()
            ->where('id', $id)  // Correctly match the booking by ID
            ->where('agent_id', $user->id)  // Ensure the booking belongs to the authenticated agent
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or not authorized to cancelled this booking'
            ], 404);
        }
        $property = Property::query()->find($booking->property_id);
        if ($property->user->id == $user->id) {
            // Mark the booking as accepted (assuming you have a status field)
            $booking->status = BookingStatus::CANCELLED;  // Adjust field name as per your schema
            $booking->save();

            return response()->json([
                'status' => true,
                'message' => 'Booking cancelled successfully',
                'booking' => $booking
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You\'re not authorize to perform this action'
            ], 401);
        }
    }

    // Implement show, update, destroy if necessary
}
