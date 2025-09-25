<?php

namespace App\Http\Controllers\Web\Backend\CMS\CourseOverview;

use App\Http\Controllers\Controller;
use App\Models\Learn;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    /**
     * ✅ List all learns
     */
    public function index()
    {
        $learns = Learn::with(['course', 'user'])
            ->latest()
            ->paginate(10);

        return view('backend.layouts.CourseOverview.list', compact('learns'));
    }

    /**
     * ✅ Show create form
     */
    public function create()
    {
        // সব course dropdown এর জন্য পাঠানো হচ্ছে
        $courses = OnlineCourse::all();
        return view('backend.layouts.CourseOverview.add', compact('courses'));
    }

    /**
     * Store new Learn
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:online_courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|array', // multiple textarea থেকে array আসবে
        ]);

        Learn::create([
            'course_id'   => $request->course_id,
            'user_id'     => Auth::id(), // বর্তমান logged in user
            'title'       => $request->title,
            'description' => $request->description, // auto JSON cast হবে
        ]);

        return redirect()->route('web-overview.index')
            ->with('success', 'Course Overview created successfully!');
    }

    /**
     * ✅ Show details
     */
    public function show($id)
    {
        $learn = Learn::with(['course', 'user'])->findOrFail($id);
        return view('backend.layouts.CourseOverview.show', compact('learn'));
    }

    /**
     * ✅ Show edit form
     */
    public function edit($id)
    {
        $learn = Learn::findOrFail($id);
        $courses = OnlineCourse::all();
        return view('backend.layouts.CourseOverview.edit', compact('learn', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id'   => 'required|exists:online_courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|array',
        ]);

        $learn = Learn::findOrFail($id);

        $learn->update([
            'course_id'   => $request->course_id,
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('web-overview.index')->with('success', 'Course Overview updated successfully!');
    }

    /**
     * ✅ Delete learn
     */
    public function destroy($id)
    {
        $learn = Learn::findOrFail($id);

        // Delete record
        $learn->delete();

        return redirect()->route('web-overview.index')->with('success', 'Course Overview deleted successfully!');
    }
}
