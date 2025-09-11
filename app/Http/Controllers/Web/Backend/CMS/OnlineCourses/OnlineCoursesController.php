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

        return redirect()->route('online-courses.index')->with('message', 'Course created successfully!');
    }
    public function edit(OnlineCourse $online_course)
    {
        // Fetch all users and categories for dropdowns
        $users = User::all();
        $categories = Category::all();

        // Pass the course, users, and categories to the edit view
        return view('backend.layouts.online_courses.edit', compact('online_course', 'users', 'categories'));
    }
    public function update(Request $request, OnlineCourse $online_course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'level' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png', // optional on update
            'rating_id' => 'nullable|integer',
            'category_id' => 'required|exists:categories,id',
            'course_type' => 'nullable|in:free,paid', // optional from form
        ]);

        $data = $request->only([
            'title', 'description', 'price', 'level', 'duration', 'language', 'rating_id', 'category_id','course_type'
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($online_course->image && file_exists(public_path('uploads/courses/' . $online_course->image))) {
                unlink(public_path('uploads/courses/' . $online_course->image));
            }
            $image = $request->file('image');
            $imageName = time().'_'.Str::slug($request->title).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/courses'), $imageName);
            $data['image'] = $imageName;
        }

        // Set updated_by
        $data['updated_by'] = Auth::id();

        // Determine course type based on price
//        if (isset($data['price']) && $data['price'] > 0) {
//            $data['course_type'] = 'paid';
//        } else {
//            $data['course_type'] = 'free';
//            $data['price'] = 0; // ensure price is 0 for free courses
//        }

        $online_course->update($data);

        return redirect()->route('online-courses.index')->with('message', 'Course updated successfully!');
    }

    public function destroy(OnlineCourse $online_course)
    {
        // Delete image file if exists
        if ($online_course->image && file_exists(public_path('uploads/courses/' . $online_course->image))) {
            unlink(public_path('uploads/courses/' . $online_course->image));
        }

        // Delete the course record
        $online_course->delete();

        return redirect()->route('online-courses.index')->with('message', 'Course deleted successfully!');
    }


}
