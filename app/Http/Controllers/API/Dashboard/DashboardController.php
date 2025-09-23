<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Logged-in user

        // Get all enrollments with related course info
        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->get();

        // Calculate stats
        $totalCourses = $enrollments->count();  // Total enrollments
        $inProgress   = $enrollments->where('status', 'success')->count(); // Completed
        $inComplete   = $enrollments->where('status', '!=', 'success')->count(); // Pending or failed
        $certificates = $inProgress; // Certificates are awarded for completed courses

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

    //    public function update(Request $request)
    //    {
    //        $user = $request->user();
    //
    //        // Merge JSON input if sent as JSON
    //        $request->merge($request->json()->all());
    //
    //
    //        $validated = $request->validate([
    //            'name' => 'nullable|string|max:255',
    //            'email' => 'nullable|email|max:255|',  // no update
    //            'phone' => 'nullable|string|max:20',
    //            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //        ]);
    //
    //        // Handle profile image upload
    //        if ($request->hasFile('profile_image')) {
    //            // Delete old image if exists
    //            if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
    //                Storage::delete('public/' . $user->profile_image);
    //            }
    //
    //            // Store new image
    //            $path = $request->file('profile_image')->store('profile_images', 'public');
    //            $validated['profile_image'] = $path;
    //        }
    //
    //        // Update only provided fields
    //        foreach ($validated as $key => $value) {
    //            $user->$key = $value;
    //        }
    //        $user->save();
    //
    //        return response()->json([
    //            'status' => true,
    //            'message' => 'Profile updated successfully',
    //            'user' => $user
    //        ]);
    //    }










    //user info get 
    public function userprofileinfo(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        //check 
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        //response 
        return response()->json([
            'status' => true,
            'message' => 'User profile fetched successfully',
            'user' => $user
        ]);
    }




    // public function userprofileupdate(Request $request)
    // {
    //     $user = $request->user();
    //     if (!$user) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'User not authenticated'
    //         ], 401);
    //     }

    //     // Validate input data
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:20',
    //         'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $validated = $validator->validated();

    //     // Handle profile image upload
    //     if ($request->hasFile('profile_image')) {
    //         $file = $request->file('profile_image');
    //         $filename = time() . '_' . $file->getClientOriginalName();

    //         // Move the file to public/profile_images
    //         $file->move(public_path('profile_images'), $filename);

    //         // Delete old profile image if exists
    //         if ($user->profile_image) {
    //             $oldPath = public_path('profile_images/' . basename($user->profile_image));
    //             if (file_exists($oldPath)) {
    //                 unlink($oldPath);
    //             }
    //         }

    //         // Save filename in DB
    //         $validated['profile_image'] = $filename;
    //     }


    //     $user->update($validated);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Profile updated successfully',
    //         'user' => $user
    //     ]);
    // }


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

        // Move the file to public/profile_images
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

}
