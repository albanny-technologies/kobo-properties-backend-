<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function agents()
    {
        return response()->json([
            'status' => true,
            'agents' => User::where('type', UserType::Agent)->get()
        ]);
    }
}
