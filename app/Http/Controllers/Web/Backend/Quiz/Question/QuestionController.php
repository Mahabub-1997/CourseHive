<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Question;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // List all questions
    public function index()
    {
        $questions = Question::with('quiz')->latest()->paginate(10);
        return view('backend.layouts.quiz.question.list', compact('questions'));
    }

    // Show form to create
    public function create()
    {
        $quizzes = Quiz::all();
        return view('backend.layouts.quiz.question.add', compact('quizzes'));
    }

    // Store new question
    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
        ]);

        Question::create($request->all());

        return redirect()->route('questions.index')->with('success', 'Question created successfully.');
    }

    // Show form to edit
    public function edit(Question $question)
    {
        $quizzes = Quiz::all();
        return view('backend.layouts.quiz.question.edit', compact('question', 'quizzes'));
    }
    public function update(Request $request, Question $question)
    {
        // Validate the incoming request
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
        ]);

        // Update the question
        $question->update($request->all());

        // Redirect back with success message
        return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Question deleted successfully!');
    }
}
