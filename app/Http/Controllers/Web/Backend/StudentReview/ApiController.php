<?php

namespace App\Http\Controllers\Web\Backend\StudentReview;

use App\Http\Controllers\Controller;
use App\Models\ShareExperiance;
use Illuminate\Support\Facades\Http;


class ApiController extends Controller
{
    public function index()
    {
        // Fetch ShareExperiances with pagination
        $data = ShareExperiance::orderBy('created_at', 'desc')
            ->paginate(15); // 15 per page

        return view('backend.layouts.studentreview.list', compact('data'));
    }
}
