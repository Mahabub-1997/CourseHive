<?php

namespace App\Http\Controllers\Web\Backend\CMS\CourseOverview;

use App\Http\Controllers\Controller;
use App\Models\Learn;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    //  List all
    public function index()
    {
        $learns = Learn::latest()->paginate(10);
        return view('backend.layouts.CourseOverview.list', compact('learns'));
    }

    //  Create form
    public function create()
    {
        return view('backend.layouts.CourseOverview.add');
    }

    //  Store new
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|array',
        ]);

        Learn::create($request->only('title', 'description'));

        return redirect()->route('overview.index')->with('success', 'Learn created successfully!');
    }

    //  Show details (optional)
    public function show($id)
    {
        $learn = Learn::findOrFail($id);
        return view('backend.layouts.CourseOverview.show', compact('learn'));
    }

    // ✅ Edit form
    public function edit($id)
    {
        $learn = Learn::findOrFail($id);
        return view('backend.layouts.CourseOverview.edit', compact('learn'));
    }

    // ✅ Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|array',
        ]);

        $learn = Learn::findOrFail($id);
        $learn->update($request->only('title', 'description'));

        return redirect()->route('overview.index')->with('success', 'Learn updated successfully!');
    }

    // ✅ Delete
    public function destroy($id)
    {
        $learn = Learn::findOrFail($id);
        $learn->delete();

        return redirect()->route('overview.index')->with('success', 'Learn deleted successfully!');
    }
}
