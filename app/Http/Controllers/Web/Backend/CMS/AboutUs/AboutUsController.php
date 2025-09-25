<?php

namespace App\Http\Controllers\Web\Backend\CMS\AboutUs;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\OnlineCourse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUsRecords = AboutUs::latest()->paginate(10);
        $totalCourses = OnlineCourse::count();
        return view('backend.layouts.about_us.list', compact('aboutUsRecords','totalCourses'));
    }
    public function create()
    {
        return view('backend.layouts.about_us.add');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validatedData['image'] = $request->file('image')->store('about-us', 'public');
        }

        AboutUs::create($validatedData);

        return redirect()->route('web-about-us.index')->with('success', 'About Us created successfully!');
    }

    public function edit($id)
    {
        // Find the About Us record or fail
        $aboutUs = AboutUs::findOrFail($id);

        // Return the edit view with the record
        return view('backend.layouts.about_us.edit', compact('aboutUs'));
    }

    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => now(),
        ];

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get old image
            $oldImage = DB::table('about_us')->where('id', $id)->value('image');

            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // Store new image
            $path = $request->file('image')->store('about-us', 'public');
            $data['image'] = $path;
        }

        // Update record directly in DB
        $updated = DB::table('about_us')->where('id', $id)->update($data);

        if ($updated) {
            return redirect()->route('web-about-us.index')->with('message', 'About Us updated successfully!');
        } else {
            return redirect()->back()->with('message', 'Failed to update About Us.')->withInput();
        }
    }

    public function destroy($id)
    {
        // Get the record's image path
        $image = DB::table('about_us')->where('id', $id)->value('image');

        // Delete the image file if it exists
        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }

        // Delete the record from the database
        $deleted = DB::table('about_us')->where('id', $id)->delete();

        // Redirect back with a message
        if ($deleted) {
            return redirect()->route('web-about-us.index')->with('message', 'About Us record deleted successfully!');
        } else {
            return redirect()->back()->with('message', 'Failed to delete About Us record.');
        }
    }
}
