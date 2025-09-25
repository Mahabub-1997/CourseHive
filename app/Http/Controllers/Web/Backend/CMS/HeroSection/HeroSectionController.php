<?php

namespace App\Http\Controllers\Web\Backend\CMS\HeroSection;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    /**
     * Display a listing of hero sections.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch latest hero sections and paginate (10 per page)
        $heroSections = HeroSection::latest()->paginate(10);

        // Return the list view with hero sections
        return view('backend.layouts.hero_sections.list', compact('heroSections'));
    }

    /**
     * Show the form for creating a new hero section.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('backend.layouts.hero_sections.add');
    }

    /**
     * Store a newly created hero section in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //  Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        //  Prepare data for insertion
        $data = $request->only(['title', 'description']);

        //  Handle image upload if provided
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        //  Create hero section record
        HeroSection::create($data);

        //  Redirect to index with success message
        return redirect()->route('web-hero-sections.index')
            ->with('success', 'Hero Section created successfully.');
    }

    /**
     * Show the form for editing the specified hero section.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        //  Retrieve hero section by ID or fail with 404
        $heroSection = HeroSection::findOrFail($id);

        //  Pass the hero section to the edit view
        return view('backend.layouts.hero_sections.edit', compact('heroSection'));
    }

    /**
     * Update the specified hero section in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //  Retrieve hero section by ID or fail with 404
        $heroSection = HeroSection::findOrFail($id);

        //  Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        //  Prepare data for update
        $data = $request->only(['title', 'description']);

        //  Handle image upload if provided
        if ($request->hasFile('image')) {

            // Delete old image if exists
            if ($heroSection->image && Storage::disk('public')->exists($heroSection->image)) {
                Storage::disk('public')->delete($heroSection->image);
            }

            // Store new image
            $data['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        // Update the hero section record
        $heroSection->update($data);

        // Redirect back with success message
        return redirect()->route('web-hero-sections.index')
            ->with('success', 'Hero Section updated successfully.');
    }

    /**
     * Remove the specified hero section from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        $heroSection = HeroSection::findOrFail($id);
        if ($heroSection->image && Storage::disk('public')->exists($heroSection->image)) {
            Storage::disk('public')->delete($heroSection->image);
        }
        $heroSection->delete();

        return redirect()->route('web-hero-sections.index')
            ->with('success', 'Hero Section deleted successfully.');
    }
}
