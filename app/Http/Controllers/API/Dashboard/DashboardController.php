<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Dashboard main data
     * Returns user info, enrollments, and stats
     */
    public function index(Request $request)
    {
        $user = $request->user(); // Authenticated user

        // Get all enrollments with related course info
        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->get();

        // Calculate stats
        $totalCourses = $enrollments->count();  // Total enrollments
        $inProgress   = $enrollments->where('status', 'success')->count(); // Completed courses
        $inComplete   = $enrollments->where('status', '!=', 'success')->count(); // Pending or failed
        $certificates = $inProgress; // Certificates awarded for completed courses

        // Prepare response
        return response()->json([
            'message' => 'Welcome to dashboard',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_verified' => $user->is_verified,
                'created_at' => $user->created_at,
            ],
            'stats' => [
                'totalCourses' => $totalCourses,
                'inProgress' => $inProgress,
                'inComplete' => $inComplete,
                'certificates' => $certificates,
            ],
            'enrollments' => $enrollments->map(function ($enrollment) {
                return [
                    'course_id' => $enrollment->course->id,
                    'course_title' => $enrollment->course->title,
                    'status' => $enrollment->status,
                    'enrolled_at' => $enrollment->enrolled_at,
                ];
            }),
        ]);
    }

    /**
     * Get authenticated user profile info
     */
    public function userprofileinfo(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully',
            'user' => $user
        ]);
    }

    /**
     * Update user profile
     * Supports name, phone, and profile image upload
     */
    public function userprofileupdate(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Move file to public/profile_images
            $file->move(public_path('profile_images'), $filename);

            // Delete old image if exists
            if ($user->profile_image && file_exists(public_path('profile_images/' . $user->profile_image))) {
                unlink(public_path('profile_images/' . $user->profile_image));
            }

            $validated['profile_image'] = $filename;
        }

        // Update user
        $user->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
//    new added
    public function updatePassword(Request $request)
    {
        $user = $request->user(); // Authenticated user

        // Validate request
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' checks password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 403);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully!'
        ]);
    }
}
