<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Property;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function properties()
    {
        $user = auth()->user();
        $properties = Property::query()->where('user_id', $user->id)->where('type', UserType::Agent)->get();
        return response()->json([
            'status' => true,
            'properties' =>$properties
        ]);
    }
}
