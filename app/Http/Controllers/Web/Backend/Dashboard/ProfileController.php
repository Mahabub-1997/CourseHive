<?php

namespace App\Http\Controllers\Web\Backend\Dashboard;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */


    public function index()
    {
        $userId = auth()->id();

        // Get all enrollments for the logged-in user
        $enrollments = Enrollment::where('user_id', $userId)->get();

        // Total courses the user is enrolled in
        $totalCourses = $enrollments->count();

        // Completed courses
        $inProgress = $enrollments->where('status', 'success')->count();

        // Incomplete courses
        $inComplete = $enrollments->where('status', '!=', 'success')->count();

        return view('backend.layouts.dashboard', compact(
            'totalCourses',
            'inProgress',
            'inComplete',
            'enrollments'
        ));
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
