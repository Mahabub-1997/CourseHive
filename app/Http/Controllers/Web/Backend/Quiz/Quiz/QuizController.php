<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('part')->latest()->paginate(10);
        return view('backend.layouts.quiz.quiz.list', compact('quizzes'));
    }

    public function create()
    {
        $parts = Part::all(); // dropdown
        return view('backend.layouts.quiz.quiz.add', compact('parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'title'   => 'nullable|string|max:255',
        ]);

        Quiz::create($request->all());

        return redirect()->route('quizzes.index')->with('success', 'Quiz created successfully!');
    }
    public function show(Quiz $quiz)
    {
        return view('backend.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $parts = Part::all();
        return view('backend.layouts.quiz.quiz.edit', compact('quiz', 'parts'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'title'   => 'nullable|string|max:255',
        ]);

        $quiz->update($request->all());

        return redirect()->route('quizzes.index')->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully!');
    }
}
