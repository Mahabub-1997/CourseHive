<?php

namespace App\Http\Controllers\Web\Backend\StudentReview;

use App\Http\Controllers\Controller;
use App\Models\ShareExperiance;
use Illuminate\Support\Facades\Http;


class ApiController extends Controller
{
    public function index()
    {
        // Fetch all ShareExperiances from the database
        $data = ShareExperiance::all();

        // Pass data to Blade view
        return view('backend.layouts.studentreview.list', compact('data'));
    }
}
