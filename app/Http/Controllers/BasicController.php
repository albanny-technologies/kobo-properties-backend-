<?php

namespace App\Http\Controllers;

use App\Models\Frontend;
use App\Models\GeneralSettings;
use App\Models\Payment;
use App\Models\SubPayment;
use Illuminate\Http\Request;
use Laravelcm\Subscriptions\Models\Plan;

class BasicController extends Controller
{
    public function index()
    {
        $plans = Plan::with('features')->get();
        $general = GeneralSettings::first();
        $frontend = Frontend::first();
        $payment = Payment::query()->first();
        return response()->json([
            'status' => true,
            'site_info' => [
                'phone_no' => $general->phone_number,
                'email' => $general->email,
                'logo' => 'https://koboproperties.com/'.$general->main_logo,
                'footer_logo' => 'https://koboproperties.com/'.$general->footer_logo,
                'about_us' => $frontend->about_us,
                'terms' => $frontend->terms,
                'privacy' => $frontend->privacy,
                'footer_info' => $frontend->footer_info,
                'address' => $general->address,
                'socials' => [
                    'facebook' => $frontend->facebook,
                    'instagram' => $frontend->instagram,
                    'twitter' => $frontend->twitter
                ]
            ],
            'counter_no' => [
                'total_bookings' => 393,
                'active_client' => 4200,
                'total_agents' => 239
            ],
            'account_details' => [
                'account_name' => $payment->acct_name,
                'account_no' => $payment->acct_no,
                'bank_name' => $payment->bank_name,
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
