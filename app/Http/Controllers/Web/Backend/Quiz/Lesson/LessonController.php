<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // Display list of lessons
    public function index()
    {
        $lessons = Lesson::with('course')->paginate(10);

        return view('backend.layouts.quiz.lesson.list', compact('lessons'));
    }

    // Show create form
    public function create()
    {
        $courses = OnlineCourse::all(); // To select which course

        return view('backend.layouts.quiz.lesson.add', compact('courses'));
    }

    // Store lesson
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:online_courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Lesson::create($request->all());

        return redirect()->route('web-lessons.index')
            ->with('success', 'Lesson created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        $courses = OnlineCourse::all();

        return view('backend.layouts.quiz.lesson.edit', compact('lesson', 'courses'));
    }

    // Update lesson
    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        // Validate input
        $request->validate([
            'course_id' => 'required|exists:online_courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lesson->update($request->only(['course_id', 'title', 'description']));

        return redirect()->route('web-lessons.index')
            ->with('success', 'Lesson updated successfully.');
    }

    // Delete lesson
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('web-lessons.index')
            ->with('success', 'Lesson deleted successfully.');
    }
}
