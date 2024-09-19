<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'firstname' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'phone_no' => 'nullable|string|max:20',
            'alt_phone_no' => 'nullable|string|max:20',
            'company_name' => 'nullable',
            'address' => 'nullable',
            'alt_phone_no' => 'nullable',
            'cac_doc' => 'nullable',
            'phone_no' => 'nullable',
            'description' => 'nullable',
        ]);

        $user->update($validatedData);

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }
}
