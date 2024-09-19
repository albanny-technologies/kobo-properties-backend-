<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PropertyController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Optionally, get pagination size from request or use default

        // Paginate properties to improve performance with large datasets
        $properties = Property::with('images', 'videos')->get();

        // Check if properties exist
        if ($properties->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No properties found'
            ], 404);
        }

        // Return paginated properties with metadata (current page, total, etc.)
        return response()->json([
            'status' => true,
            'properties' => $properties
        ], 200);
    }


    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'additional_charge' => 'nullable|numeric',
            'landmarks' => 'nullable|array',
            'landmarks.*' => 'string|max:255',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:255',
            'size' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,avi|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|mimes:mp4,avi|max:10240',
        ]);

        // Create a new Property instance with validated data
        $property = new Property();
        $property->title = $validatedData['title'];
        $property->location = $validatedData['location'];
        $property->state = $validatedData['state'];
        $property->city = $validatedData['city'];
        $property->additional_charge = $validatedData['additional_charge'] ?? null;
        $property->size = $validatedData['size'] ?? null;
        $property->user_id = $request->user()->id;
        $property->landmarks = json_encode($validatedData['landmarks'] ?? []);
        $property->amenities = json_encode($validatedData['amenities'] ?? []);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('properties/images', 'public');
            $property->image = url('storage/' . $path);  // Save the full URL
        }

        // Handle main video upload
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('properties/videos', 'public');
            $property->video = url('storage/' . $path);  // Save the full URL
        }

        $property->save();

        // Handle additional images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties/images', 'public');
                $property->images()->create(['path' => url('storage/' . $path)]);  // Save the full URL
            }
        }

        // Handle additional videos upload
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('properties/videos', 'public');
                $property->videos()->create(['path' => url('storage/' . $path)]);  // Save the full URL
            }
        }

        // Return a structured response
        return response()->json([
            'message' => 'Property created successfully.',
            'data' => $property->load('images', 'videos'),
        ], 201);
    }

    public function update(Request $request, Property $property)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'additional_charge' => 'nullable|numeric',
            'landmarks' => 'nullable|array',
            'landmarks.*' => 'string|max:255', // Ensure each landmark is a string
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:255', // Ensure each amenity is a string
            'size' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max size 2MB
            'video' => 'nullable|mimes:mp4,avi|max:10240', // Max size 10MB
            'images' => 'nullable|array', // Handle other images as array
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos' => 'nullable|array', // Handle other videos as array
            'videos.*' => 'nullable|mimes:mp4,avi|max:10240',
        ]);

        // Update property details with validated data
        $property->title = $validatedData['title'];
        $property->location = $validatedData['location'];
        $property->state = $validatedData['state'];
        $property->city = $validatedData['city'];
        $property->additional_charge = $validatedData['additional_charge'] ?? $property->additional_charge;
        $property->size = $validatedData['size'] ?? $property->size;
        $property->landmarks = json_encode($validatedData['landmarks'] ?? json_decode($property->landmarks, true));
        $property->amenities = json_encode($validatedData['amenities'] ?? json_decode($property->amenities, true));

        // Handle main image update
        if ($request->hasFile('image')) {
            // Delete the old image file if it exists
            if ($property->image) {
                Storage::disk('public')->delete(str_replace(url('storage/'), '', $property->image));
            }
            $path = $request->file('image')->store('properties/images', 'public');
            $property->image = url('storage/' . $path); // Save the new image URL
        }

        // Handle main video update
        if ($request->hasFile('video')) {
            // Delete the old video file if it exists
            if ($property->video) {
                Storage::disk('public')->delete(str_replace(url('storage/'), '', $property->video));
            }
            $path = $request->file('video')->store('properties/videos', 'public');
            $property->video = url('storage/' . $path); // Save the new video URL
        }

        $property->save();

        // Handle additional images update
        if ($request->hasFile('images')) {
            // Optionally, delete old images if needed
            // $property->images()->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('properties/images', 'public');
                $property->images()->create(['path' => url('storage/' . $path)]);  // Save the new image URL
            }
        }

        // Handle additional videos update
        if ($request->hasFile('videos')) {
            // Optionally, delete old videos if needed
            // $property->videos()->delete();

            foreach ($request->file('videos') as $video) {
                $path = $video->store('properties/videos', 'public');
                $property->videos()->create(['path' => url('storage/' . $path)]);  // Save the new video URL
            }
        }

        // Return a structured response with the updated property
        return response()->json([
            'status' => true,
            'message' => 'Property updated successfully.',
            'data' => $property->load('images', 'videos'), // Include related images and videos in response
        ], 200);
    }


    public function show(Property $property)
    {
        return response()->json([
            'status' => true,
            'property' => $property->load('images', 'videos')
        ]);
    }


    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        $property->delete();

        return response()->json([
            'status' => true,
            'message' => 'Property deleted succesfully'
        ], 200);
    }
    public function compare(Request $request)
    {
        $propertyIds = $request->input('ids'); // Expecting an array of property IDs

        $properties = Property::whereIn('id', $propertyIds)->get();

        return response()->json([
            'status' => true,
            'properties' => $properties
        ]);
    }
}
