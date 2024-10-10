<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'site_info' => [
                'phone_no' => 2385058658999,
                'email' => 'info@koboproperties.com',
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
            ]

        ]);
    }
}
