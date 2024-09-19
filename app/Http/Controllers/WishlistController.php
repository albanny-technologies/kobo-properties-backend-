<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'status' => true,
            'wishlist' => $user->wishlist->load('property')
        ]);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $validateData = $request->validate([
            'property_id' => 'required|exists:properties,id'
        ]);
        $propertyExist = Wishlist::where('id', $validateData['property_id'])->where('user_id', $user->id)->first();
        if($propertyExist){
            return response()->json([
                'status' => false,
                'message' => 'You\'ve added this property to your wishlist already'
            ], 400);
        }
        $wishlist = new Wishlist();
        $wishlist->user_id = $user->id;
        $wishlist->property_id = $validateData['property_id'];
        $wishlist->save();

        return response()->json([
            'status' => true,
            'message' => 'Property added to favourites',
            'wishlist' => $wishlist
        ]);
    }
}
