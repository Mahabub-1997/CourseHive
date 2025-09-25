<?php

namespace App\Http\Controllers\Web\Backend\CMS\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch latest categories and paginate (10 per page)
        $categories = Category::latest()->paginate(10);

        // Return the list view with categories
        return view('backend.layouts.category.list', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('backend.layouts.category.add');
    }

    /**
     * Store a newly created category in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        // Create a new category record
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Redirect to category index with success message
        return redirect()->route('web-categories.index')
            ->with('message', 'Category created successfully!');
    }

    /**
     * Show the form for editing an existing category.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Retrieve the category or fail with 404
        $category = Category::findOrFail($id);

        // Return the edit view with the category
        return view('backend.layouts.category.edit', compact('category'));
    }

    /**
     * Update the specified category in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Retrieve the category by ID
        $category = Category::findOrFail($id);

        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // Update category data
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Redirect with success message
        return redirect()->route('web-categories.index')
            ->with('message', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Retrieve the category or fail with 404
        $category = Category::findOrFail($id);

        // Delete the category
        $category->delete();

        // Redirect with success message
        return redirect()->route('web-categories.index')
            ->with('message', 'Category deleted successfully!');
    }
}
