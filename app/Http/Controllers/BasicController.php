<?php

namespace App\Http\Controllers;

use App\Models\SubPayment;
use Illuminate\Http\Request;
use Laravelcm\Subscriptions\Models\Plan;

class BasicController extends Controller
{
    public function index()
    {
        $plans = Plan::with('features')->get();
        return response()->json([
            'status' => true,
            'site_info' => [
                'phone_no' => 2348062765353,
                'email' => 'info@koboproperties.com',
                'logo' => 'https://koboproperties.com/logo.png',
                'about_us' => 'about us goes here',
                'terms' => 'terms goes here',
                'footer_info' => 'footer info goes here'
            ],
            'counter_no' => [
                'total_bookings' => 393,
                'active_client' => 4200,
                'total_agents' => 239
            ],
            'account_details' => [
                'account_name' => 'John Doe',
                'account_no' => 000000000000,
                'bank_name' => 'First Bank'
            ],
            'plans' => $plans,

        ]);
    }
    public function subPayment(Request $request)
    {
        $user = auth()->user();
        $validateData = $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:4000',
        ]);
        $subPayment = new SubPayment();
        // Handle main image upload
        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('payment_proof/images', 'public');
            $subPayment->proof = url('storage/' . $path);  // Save the full URL
        }
        $subPayment->user_id = $user->id;
        $subPayment->save();
        return response()->json([
            'status' => true,
            'message' => 'Request sent successfully',
            'data' => $subPayment
        ]);
    }
}
