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
        $options = Option::with('question')->paginate(10); // Paginate 10 per page
        return view('backend.layouts.quiz.option.list', compact('options'));
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
            'option_text' => 'required|string|max:255',
            'is_correct'  => 'required|boolean',
        ]);

        Option::create($request->all());

        return redirect()->route('options.index')->with('success', 'Option created successfully.');
    }
    public function edit(Option $option)
    {
        $questions = Question::all();
        return view('backend.layouts.quiz.option.edit', compact('option', 'questions'));
    }
    public function update(Request $request, Option $option)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_text' => 'required|string|max:255',
            'is_correct'  => 'required|boolean',
        ]);

        $option->update($request->all());

        return redirect()->route('options.index')->with('success', 'Option updated successfully.');
    }
    public function destroy(Option $option)
    {
        $option->delete();
        return redirect()->route('options.index')->with('success', 'Option deleted successfully.');
    }
}
