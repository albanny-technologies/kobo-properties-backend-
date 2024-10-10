<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $review = Review::where('agent_id', $user->id)->get();
        return response()->json([
            'status' => true,
            'reviews' => $review->load('user')
        ]);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $validateData = $request->validate([
            'agent_id' => 'required|numeric|exists:users,id', // Ensures the agent exists in the database
            'review' => 'required|string|max:255',
            'rating' => 'required|numeric|min:1|max:5' // Enforce a valid rating range
        ]);

        $existingReview = Review::where('agent_id', $validateData['agent_id'])
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => false,
                'message' => 'You have already submitted a review for this agent.'
            ], 400);
        }


        $review = new Review();
        $review->agent_id = $validateData['agent_id'];
        $review->user_id = $user->id;
        $review->review = $validateData['review'];
        $review->rating = $validateData['rating'];
        $review->save();
        return response()->json([
            'status' => true,
            'message' => 'Review submitted successfully',
            'review' => $review
        ], 201); // 201 status code for resource created


    }
}
