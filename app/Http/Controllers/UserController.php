<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravelcm\Subscriptions\Models\Subscription;

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
        'website' => 'nullable',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'address' => 'nullable',
        'cac_doc' => 'nullable',
        'description' => 'nullable',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Store the image in the 'public/images' directory
        $path = $request->file('image')->store('user/images', 'public');

        // Update the user's profile with the new image path
        $validatedData['image'] =  url('storage/' . $path);  // Save the full URL
    }

    // Update user with the validated data
    $user->update($validatedData);

    return response()->json([
        'status' => true,
        'user' => $user,
    ]);
}

    public function changePassword(Request $request)
    {
        $user = $request->user();
        $password_validation = Password::min(6);

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>['error'=>$validator->errors()->all()],
            ]);
        }

        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = 'Password changes successfully';
        } else {
            $notify[] = 'The password doesn\'t match!';
        }
        return response()->json([
            'status'=>true,
            'message'=> $notify
        ]);
    }

    public function subscription(){
        $user = auth()->user();
        $subscription = Subscription::where('subscriber_id', $user->id)->first();
        if($subscription){
            return response()->json([
                'status' => true,
                'message' => 'You\'re subscribed to the '.$subscription->name.' plan.',
                'subscription' => $subscription,

            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'You\'re not subscribed to any plans.',
            ], 400);
        }
    }
}
