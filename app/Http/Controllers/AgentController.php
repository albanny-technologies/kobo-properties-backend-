<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'agents' => User::where('type', UserType::Agent)->get()
        ]);
    }
    public function show($id)
    {
        $agent = User::query()->where('type', UserType::Agent)->where('id', $id)->first();

        if(!$agent)
        {
            return response()->json([
                'status' => false,
                'message' => 'agent no found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'agent' => $agent->load('properties', 'reviews')
        ]);
    }
}
