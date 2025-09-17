<?php

namespace App\Http\Controllers\Web\Backend\Quiz\Option;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    // Display all options
    public function index()
    {
        // Load questions with their options (eager load). Adjust pagination as needed.
        $questions = Question::with(['options' => function ($q) {
            $q->orderBy('id'); // optional: ensure stable ordering
        }])->paginate(10);

        // Return the view that expects $questions
        return view('backend.layouts.quiz.option.list', compact('questions'));
    }

    // Show form to create new option
    public function create()
    {
        $questions = Question::all();
        return view('backend.layouts.quiz.option.add', compact('questions'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'options'     => 'required|array|min:1',
            'options.*.en' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        foreach ($request->options as $option) {
            Option::create([
                'question_id' => $request->question_id,
                'option_text' => [
                    'en' => $option['en'] ?? '',
                    'bn' => $option['bn'] ?? '',
                ],
                'is_correct'  => $option['is_correct'],
            ]);
        }

        return redirect()->route('options.index')->with('success', 'Options added successfully.');
    }



    public function edit($questionId)
    {
        // Load the question with its options
        $question = Question::with('options')->findOrFail($questionId);
        $questions = Question::all(); // for dropdown
        $options   = $question->options; // all options of this question

        return view('backend.layouts.quiz.option.edit', compact('question', 'questions', 'options'));
    }
    public function update(Request $request, $questionId)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'options.*.id' => 'required|exists:options,id',
            'options.*.en' => 'required|string|max:255',
            'options.*.is_correct' => 'required|boolean',
        ]);

        foreach ($request->options as $optionData) {
            $option = Option::find($optionData['id']);
            if ($option) {
                $option->update([
                    'option_text' => $optionData['en'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }

        return redirect()->route('options.index')->with('success', 'Options updated successfully.');
    }
    public function destroy($questionId)
    {
        $question = Question::with('options')->findOrFail($questionId);

        // Delete all options first
        $question->options()->delete();

        // Then delete the question itself
        $question->delete();

        return redirect()->route('options.index')->with('success', 'Question and its options deleted successfully.');
    }
}
