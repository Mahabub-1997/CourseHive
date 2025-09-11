<?php

namespace App\Http\Controllers\API\CMS;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroImageController extends Controller


{
    /**
     * Display a listing of hero images.
     */
    public function index()
    {
        $heroImages = HeroImage::all();
        return response()->json($heroImages);
    }

    /**
     * Store a newly created hero images in storage.
     */
    public function store(Request $request)
    {
        // Validate that 'images' is required and each file meets requirements
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('hero-images', $filename, 'public');
                $uploadedImages[] = $path;
            }
        }

        // Prevent creating a record if no images were uploaded
        if (empty($uploadedImages)) {
            return response()->json(['message' => 'No images uploaded'], 422);
        }

        $heroImage = HeroImage::create([
            'images' => $uploadedImages, // Eloquent casts array to JSON automatically
        ]);

        return response()->json($heroImage, 201);
    }

    /**
     * Display the specified hero image.
     */
    public function show(HeroImage $heroImage)
    {
        return response()->json($heroImage);
    }

    /**
     * Update the specified hero images in storage.
     */
    public function update(Request $request, HeroImage $heroImage)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $existingImages = $heroImage->images ?? [];

        $uploadedImages = $existingImages;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('hero-images', $filename, 'public');
                $uploadedImages[] = $path;
            }
        }

        $heroImage->update([
            'images' => $uploadedImages,
        ]);

        return response()->json($heroImage);
    }

    /**
     * Remove the specified hero images from storage.
     */
    public function destroy(HeroImage $heroImage)
    {
        // Delete images from storage
        if ($heroImage->images) {
            foreach ($heroImage->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $heroImage->delete();

        return response()->json(['message' => 'Hero images deleted successfully']);
    }
}
