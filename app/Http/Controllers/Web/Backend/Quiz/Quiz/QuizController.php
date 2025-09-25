<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Display list of quizzes
    public function index()
    {
        $quizzes = Quiz::with('part')->latest()->paginate(10);

        return view('backend.layouts.quiz.quiz.list', compact('quizzes'));
    }

    // Show create form
    public function create()
    {
        $parts = Part::all(); // dropdown

        return view('backend.layouts.quiz.quiz.add', compact('parts'));
    }

    // Store new quiz
    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'title'   => 'nullable|string|max:255',
        ]);

        Quiz::create($request->all());

        return redirect()->route('web-quizzes.index')
            ->with('success', 'Quiz created successfully!');
    }

    // Show a specific quiz
    public function show(Quiz $quiz)
    {
        return view('backend.quizzes.show', compact('quiz'));
    }

    // Show the edit form for a quiz
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $parts = Part::all();

        return view('backend.layouts.quiz.quiz.edit', compact('quiz', 'parts'));
    }

    // Update a specific quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'title'   => 'nullable|string|max:255',
        ]);

        $quiz->update($request->only(['part_id', 'title']));

        return redirect()->route('web-quizzes.index')
            ->with('success', 'Quiz updated successfully!');
    }

    // Delete a quiz
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('web-quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }
}
