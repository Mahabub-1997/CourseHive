<?php

namespace App\Http\Controllers\Web\Backend\CMS\HeroImage;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;

class HeroImageController extends Controller
{
    public function index()
    {
        $heroImages = HeroImage::latest()->paginate(10);
        return view('backend.layouts.hero_images.list', compact('heroImages'));
    }


    public function create()
    {
        return view('backend.layouts.hero_images.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $paths = [];
        foreach ($request->file('images') as $file) {
            $paths[] = $file->store('hero_images', 'public');
        }

        HeroImage::create(['images' => $paths]);

        return redirect()->route('web-hero-images.index')->with('success', 'Images uploaded successfully.');
    }
    public function edit($id)
    {

        $heroImage = HeroImage::findOrFail($id);
        return view('backend.layouts.hero_images.edit', compact('heroImage'));
    }
    public function update(Request $request, $id)
    {
        // Retrieve the hero image record or fail with 404
        $heroImage = HeroImage::findOrFail($id);

        // Validate input
        $request->validate([
            'images' => 'nullable|array|max:10', // max 10 images
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB per image
        ]);

        //  If new images are uploaded
        if ($request->hasFile('images')) {

            // a) Delete old images from storage
            if (is_array($heroImage->images)) {
                foreach ($heroImage->images as $oldImage) {
                    $filePath = storage_path('app/public/' . $oldImage);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // b) Store new images and collect paths
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('hero_images', 'public');
            }

            // c) Update the hero image record
            $heroImage->update(['images' => $paths]);
        }

        // Redirect back with success message
        return redirect()->route('web-hero-images.index')
            ->with('success', 'Hero images updated successfully.');
    }
    public function destroy($id)
    {

        $heroImage = HeroImage::findOrFail($id);
        if (is_array($heroImage->images)) {
            foreach ($heroImage->images as $img) {
                $filePath = storage_path('app/public/' . $img);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        $heroImage->delete();

        return redirect()->route('web-hero-images.index')
            ->with('success', 'Hero images deleted successfully.');
    }
}
