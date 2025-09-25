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

    // Show form to create a new question
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

        return redirect()->route('web-questions.index')
            ->with('success', 'Question created successfully.');
    }

    // Show form to edit a question
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $quizzes = Quiz::all();

        return view('backend.layouts.quiz.question.edit', compact('question', 'quizzes'));
    }

    // Update a specific question
    public function update(Request $request, $id)
    {

        $question = Question::findOrFail($id);

        //  Validate
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
        ]);


        $question->update($request->only(['quiz_id', 'question_text']));


        return redirect()->route('web-questions.index')
            ->with('success', 'Question updated successfully.');
    }

    // Delete a specific question
    public function destroy($id)
    {
        //  Retrieve the question
        $question = Question::findOrFail($id);

        // Delete the question
        $question->delete();

        //  Redirect back with success message
        return redirect()->route('web-questions.index')
            ->with('success', 'Question deleted successfully!');
    }
}
