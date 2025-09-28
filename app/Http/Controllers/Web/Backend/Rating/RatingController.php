<?php

namespace App\Http\Controllers\Web\Backend\Rating;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the ratings.
     */
    public function index(Request $request)
    {
        // optional search by user name or rating_point
        $query = Rating::with('user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%");
            })->orWhere('rating_point', 'like', "%{$q}%");
        }

        $ratings = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('backend.layouts.rating.list', compact('ratings'));
    }

    /**
     * Show the form for creating a new rating.
     */
    public function create()
    {
        // Get all users for the dropdown
        $users = User::pluck('name', 'id');

        // Get all courses for the dropdown (id and title)
        $courses = OnlineCourse::all(); // full objects to use ->id and ->title in Blade

        return view('backend.layouts.rating.add', compact('users', 'courses'));
    }

    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:online_courses,id',
            'rating_point' => 'nullable|integer|min:1|max:5',
        ]);

        // Create new rating
        Rating::create($data);

        // Redirect to ratings list with success message
        return redirect()->route('web.ratings.index') // updated route name
        ->with('success', 'Rating created successfully.');
    }

    /**
     * Display the specified rating.
     */
    public function show(Rating $rating)
    {
        $rating->load('user'); // load relations if you need them in the view
        return view('ratings.show', compact('rating'));
    }

    /**
     * Show the form for editing the specified rating.
     */
    public function edit($id)
    {
        // Fetch the rating
        $rating = Rating::findOrFail($id);

        // Users for dropdown
        $users = User::pluck('name', 'id'); // key = id, value = name

        // Courses for dropdown
        $courses = OnlineCourse::all(); // key = id, value = title

        return view('backend.layouts.rating.edit', compact('rating', 'users', 'courses'));
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(Request $request, $id)
    {
        // Fetch the rating
        $rating = Rating::findOrFail($id);

        // Validate input
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:online_courses,id',
            'rating_point' => 'nullable|integer|min:1|max:5',
        ]);

        // Update rating
        $rating->update($data);

        return redirect()->route('web.ratings.index')
            ->with('success', 'Rating updated successfully.');
    }
    /**
     * Remove the specified rating from storage.
     */
    public function destroy(Rating $rating)
    {
        $rating->delete();

        return redirect()->route('web.ratings.index')
            ->with('success', 'Rating deleted successfully.');
    }
}
