<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Part;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartController extends Controller
{
    /**
     * Display a list of parts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $parts = Part::with('lesson')->latest()->paginate(10);
        $partsCount = Part::count();

        return view('backend.layouts.quiz.part.list', compact('parts', 'partsCount'));
    }

    /**
     * Show the form for creating a new part.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $lessons = Lesson::all(); // For dropdown

        return view('backend.layouts.quiz.part.add', compact('lessons'));
    }

    /**
     * Store a newly created part in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //  Validate input
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', // max 50MB
        ]);

        //  Handle video file upload if provided
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('parts', 'public');
        }

        //  Create part record
        Part::create([
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'content' => $request->content,
            'video' => $videoPath,
        ]);

        // Redirect back with success message
        return redirect()->route('web-parts.index')
            ->with('success', 'Part created successfully!');
    }

    /**
     * Display the specified part.
     *
     * @param \App\Models\Part $part
     * @return \Illuminate\View\View
     */
    public function show(Part $part)
    {
        return view('backend.parts.show', compact('part'));
    }

    /**
     * Show the form for editing the specified part.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $part = Part::findOrFail($id);
        $lessons = Lesson::all();

        return view('backend.layouts.quiz.part.edit', compact('part', 'lessons'));
    }

    /**
     * Update the specified part in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //  Retrieve the part by ID or fail
        $part = Part::findOrFail($id);

        //  Validate input
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable', // allow file or external link
        ]);

        //  Initialize video path
        $videoPath = $part->video;

        //  Handle uploaded video file
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => 'mimes:mp4,mov,avi,wmv|max:51200', // max 50MB
            ]);

            // Delete old file if exists
            if ($part->video && Storage::disk('public')->exists($part->video)) {
                Storage::disk('public')->delete($part->video);
            }

            $videoPath = $request->file('video')->store('parts', 'public');
        }
        //  Handle external video link
        elseif ($request->filled('video') && !$request->hasFile('video')) {
            $videoPath = $request->video;
        }

        //  Update the part record
        $part->update([
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'content' => $request->content,
            'video' => $videoPath,
        ]);

        //  Redirect back with success message
        return redirect()->route('web-parts.index')
            ->with('success', 'Part updated successfully!');
    }

    /**
     * Remove the specified part from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //  Retrieve part by ID or fail
        $part = Part::findOrFail($id);

        //  Delete the part
        $part->delete();

        //  Redirect back with success message
        return redirect()->route('web-parts.index')
            ->with('success', 'Part deleted successfully!');
    }
}
