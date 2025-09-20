<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Part;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    // List all parts for a lesson
    public function index()
    {
        $parts = Part::with('lesson')->latest()->paginate(10);
        $partsCount = Part::count();
        return view('backend.layouts.quiz.part.list', compact('parts', 'partsCount'));
    }

    public function create()
    {
        $lessons = Lesson::all(); // For dropdown
        return view('backend.layouts.quiz.part.add', compact('lessons'));
    }
//    public function store(Request $request)
//    {
//        $request->validate([
//            'lesson_id' => 'required|exists:lessons,id',
//            'title' => 'required|string|max:255',
//            'content' => 'nullable|string',
//            'video' => 'nullable|string|max:255'
//        ]);
//
//        Part::create($request->all());
//
//        return redirect()->route('parts.index')->with('success', 'Part created successfully!');
//    }
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', // max 50MB
        ]);

        $videoPath = null;

        if ($request->hasFile('video')) {
            // Save video to storage/app/public/parts
            $videoPath = $request->file('video')->store('parts', 'public');
        }

        Part::create([
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'content' => $request->content,
            'video' => $videoPath,
        ]);

        return redirect()->route('parts.index')->with('success', 'Part created successfully!');
    }



    public function show(Part $part)
    {
        return view('backend.parts.show', compact('part'));
    }
    public function edit(Part $part)
    {
        $lessons = Lesson::all();
        return view('backend.layouts.quiz.part.edit', compact('part', 'lessons'));
    }

//    public function update(Request $request, Part $part)
//    {
//        $request->validate([
//            'lesson_id' => 'required|exists:lessons,id',
//            'title' => 'required|string|max:255',
//            'content' => 'nullable|string',
//            'video' => 'nullable|string|max:255'
//        ]);
//
//        $part->update($request->all());
//
//        return redirect()->route('parts.index')->with('success', 'Part updated successfully!');
//    }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            // allow both file upload and external link
            'video' => 'nullable',
        ]);

        $videoPath = $part->video;

        // Case 1: New video file uploaded
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => 'mimes:mp4,mov,avi,wmv|max:51200', // max 50MB
            ]);

            // delete old file if exists
            if ($part->video && \Storage::disk('public')->exists($part->video)) {
                \Storage::disk('public')->delete($part->video);
            }

            // save new file
            $videoPath = $request->file('video')->store('parts', 'public');
        }
        // Case 2: External video link (string)
        elseif ($request->filled('video') && !$request->hasFile('video')) {
            $videoPath = $request->video; // store YouTube/Vimeo link
        }

        $part->update([
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'content' => $request->content,
            'video' => $videoPath,
        ]);

        return redirect()->route('parts.index')->with('success', 'Part updated successfully!');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part deleted successfully!');
    }
}
