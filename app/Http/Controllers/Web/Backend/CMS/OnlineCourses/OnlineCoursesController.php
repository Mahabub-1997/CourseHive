<?php

namespace App\Http\Controllers\Web\Backend\CMS\OnlineCourses;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OnlineCoursesController extends Controller
{
    // List all courses
    public function index()
    {
        $courses = OnlineCourse::latest()->paginate(10); // fetch courses
        $users = User::all(); // Fetch all users
        $totalCourses = OnlineCourse::count();
        $inProgress = Enrollment::where('status', 'pending')->count();
        $inComplete = Enrollment::where('status', 'success')->count();
        $categories = Category::all();
        return view('backend.layouts.online_courses.list', compact('courses', 'users', 'categories', 'totalCourses', 'inProgress','inComplete'));
    }

    public function create()
    {
        $users = User::all(); // Fetch all users
        $categories = Category::all();
        return view('backend.layouts.online_courses.add', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'level' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'rating_id' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'course_type' => 'required|in:free,paid', // validate if passed from form
        ]);

        $data = $request->only([
            'title', 'description', 'price', 'level', 'duration', 'language', 'rating_id', 'category_id', 'course_type'
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.Str::slug($request->title).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/courses'), $imageName);
            $data['image'] = $imageName;
        }

        // Set user and creator
        $data['user_id'] = Auth::id();       // logged-in user as course owner
        $data['created_by'] = Auth::id();    // creator
        $data['updated_by'] = Auth::id();    // fix for NOT NULL constraint

        OnlineCourse::create($data);

        return redirect()->route('web-online-courses.index')->with('message', 'Course created successfully!');
    }

    public function edit(OnlineCourse $web_online_course)
    {
        $users = User::all();
        $categories = Category::all();
        return view('backend.layouts.online_courses.edit', [
            'online_course' => $web_online_course,
            'users' => $users,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, OnlineCourse $web_online_course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'course_type' => 'required|in:free,paid',
            'level' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'user_id' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'rating_id' => 'nullable|integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($web_online_course->image && file_exists(public_path('uploads/courses/' . $web_online_course->image))) {
                unlink(public_path('uploads/courses/' . $web_online_course->image));
            }
            $image = $request->file('image');
            $imageName = time().'_'.Str::slug($request->title).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/courses'), $imageName);
            $data['image'] = $imageName;
        }

        $data['updated_by'] = Auth::id() ?? null;

        $web_online_course->update($data);

        return redirect()->route('web-online-courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy(OnlineCourse $web_online_course)
    {
        if ($web_online_course->image && file_exists(public_path('uploads/courses/' . $web_online_course->image))) {
            unlink(public_path('uploads/courses/' . $web_online_course->image));
        }
        $web_online_course->delete();
        return redirect()->route('web-online-courses.index')->with('success', 'Course deleted successfully!');
    }


}
