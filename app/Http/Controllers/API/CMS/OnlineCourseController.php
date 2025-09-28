<?php

namespace App\Http\Controllers\API\CMS;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class OnlineCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // List all courses
    public function index()
    {
        $courses = OnlineCourse::with([
            'user',
            'rating',
            'category',
            'creator',
            'updater'
        ])->get();
        return response()->json(['success' => true, 'data' => $courses], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // Create a new course
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric',
                'level' => 'nullable|string',
                'duration' => 'nullable|string',
                'language' => 'nullable|string',
                'image' => 'nullable',
                'user_id' => 'required|exists:users,id',
                'rating_id' => 'nullable|exists:ratings,id',
                'category_id' => 'nullable|exists:categories,id',
                'created_by' => 'required|exists:users,id',
                'updated_by' => 'nullable|exists:users,id',
            ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);
            $validated['image'] = $request->file('image')->store('courses', 'public'); // e.g. storage/app/public/courses/xxxx.jpg
        } else if ($request->filled('image')) {
            // if image is provided as URL/string
            $request->validate(['image' => 'string|max:2048']);
            // you could also enforce URL: 'url'
            $validated['image'] = $request->input('image');
        }

            $course = OnlineCourse::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Online course created successfully',
                'data' => $course
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

        /**
     * Display the specified resource.
     */
    // Show a single course
    public function show($id)
    {
        try {
            $course = OnlineCourse::with(['user', 'rating', 'category', 'creator', 'updater'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $course], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // Update a course
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric',
                'level' => 'nullable|string',
                'duration' => 'nullable|string',
                'language' => 'nullable|string',
                'user_id' => 'required|exists:users,id',
                'rating_id' => 'nullable|exists:ratings,id',
                'category_id' => 'nullable|exists:categories,id',
                'updated_by' => 'nullable|exists:users,id',
                'image' => 'nullable', // conditional (file or string)
            ]);

            $course = OnlineCourse::findOrFail($id);

            // if uploading a new file
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                ]);

                // (Optional) delete old file if it was stored locally
                if ($course->image && str_starts_with($course->image, 'courses/')) {
                    \Storage::disk('public')->delete($course->image);
                }

                $validated['image'] = $request->file('image')->store('courses', 'public');
            } else if ($request->filled('image')) {
                $request->validate(['image' => 'string|max:2048']);
                $validated['image'] = $request->input('image');
            }

            $course->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Online course updated successfully',
                'data' => $course
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // Delete a course
    public function destroy($id)
    {
        try {
            $course = OnlineCourse::findOrFail($id);
            $course->delete();

            return response()->json(['success' => true, 'message' => 'Online course deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
