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
        return view('backend.layouts.quiz.part.list', compact('parts'));
    }

    public function create()
    {
        $lessons = Lesson::all(); // For dropdown
        return view('backend.layouts.quiz.part.add', compact('lessons'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|string|max:255'
        ]);

        Part::create($request->all());

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

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|string|max:255'
        ]);

        $part->update($request->all());

        return redirect()->route('parts.index')->with('success', 'Part updated successfully!');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part deleted successfully!');
    }
}
