<?php

namespace App\Http\Controllers\API\CMS;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\ShareExperiance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShareExperianceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $data = ShareExperiance::with(['onlineCourse', 'rating', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'online_course_id' => 'required|exists:online_courses,id',
            'rating_id' => 'nullable|exists:ratings,id',
            'rating_point' => 'nullable|integer|min:1|max:5',
            'user_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            if (!empty($data['rating_point'])) {
                $rating = Rating::create([
                    'user_id' => $request->user()?->id ?? $data['user_id'] ?? null,
                    'rating_point' => $data['rating_point'],
                ]);
                $data['rating_id'] = $rating->id;
            }

            if ($request->user()) {
                $data['user_id'] = $request->user()->id;
            }

            $share = ShareExperiance::create($data);

            DB::commit();
            return response()->json($share->load(['onlineCourse', 'rating', 'user']), 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
