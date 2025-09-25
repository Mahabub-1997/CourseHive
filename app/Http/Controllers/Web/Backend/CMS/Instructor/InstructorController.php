<?php

namespace App\Http\Controllers\Web\Backend\CMS\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    /**
     * Show list of instructors
     */
    public function index()
    {
        $instructors = Instructor::with(['user', 'course'])->latest()->paginate(10);
        return view('backend.layouts.CourseInstructor.list', compact('instructors'));
    }

    /**
     * Show form to create instructor
     */
    public function create()
    {
        $courses = OnlineCourse::all();
        return view('backend.layouts.CourseInstructor.add', compact('courses'));
    }

    /**
     * Store new instructor
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'    => 'required|exists:online_courses,id',
            'name'         => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'rating'       => 'nullable|numeric|min:0|max:5',
            'description'  => 'nullable|string',
            'total_lesson' => 'required|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('instructors', 'public');
        }

        Instructor::create([
            'user_id'      => Auth::id(),
            'course_id'    => $request->course_id,
            'rating_id'    => null,
            'name'         => $request->name,
            'image'        => $imagePath,
            'rating'       => $request->rating ?? 0,
            'description'  => $request->description,
            'total_lesson' => $request->total_lesson, // 👈 manual input
        ]);

        return redirect()->route('web-instructors.index')->with('success', 'Instructor created successfully.');
    }

    /**
     * Show edit form
     */
    // Edit
    public function edit($id)
    {
        $instructor = Instructor::findOrFail($id);
        $courses = OnlineCourse::all();

        return view('backend.layouts.CourseInstructor.edit', compact('instructor', 'courses'));
    }

// Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id'    => 'required|exists:online_courses,id',
            'name'         => 'required|string|max:255',
            'rating'       => 'nullable|numeric|min:0|max:5',
            'total_lesson' => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $instructor = Instructor::findOrFail($id);

        // Image handle
        if ($request->hasFile('image')) {
            if ($instructor->image && \Storage::exists($instructor->image)) {
                \Storage::delete($instructor->image);
            }
            $imagePath = $request->file('image')->store('instructors', 'public');
        } else {
            $imagePath = $instructor->image;
        }

        $instructor->update([
            'course_id'    => $request->course_id,
            'name'         => $request->name,
            'image'        => $imagePath,
            'rating'       => $request->rating,
            'total_lesson' => $request->total_lesson,
            'description'  => $request->description,
            'user_id'      => auth()->id(),
        ]);

        return redirect()->route('web-instructors.index')->with('success', 'Instructor updated successfully.');
    }

    /**
     * Delete instructor
     */
    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);

        // Delete image if exists
        if ($instructor->image && \Storage::disk('public')->exists($instructor->image)) {
            \Storage::disk('public')->delete($instructor->image);
        }

        $instructor->delete();

        return redirect()->route('web-instructors.index')->with('success', 'Instructor deleted successfully.');
    }


}
