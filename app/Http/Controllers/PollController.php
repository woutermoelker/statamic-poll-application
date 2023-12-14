<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function getQuestionsWithOptions()
    {
        $questions = Question::with('options')->orderBy('votes_count', 'desc')->get();

        return response()->json($questions);
    }




    public function createPoll(Request $request)
    {

        // Validate the request data
        $validatedData = $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array',
            'start_date' => 'date|nullable',
            'end_date' => 'date|after:start_date|nullable',
            'type' => 'required|string|max:255',
            'options.*.option_text' => 'required|string|max:255'
        ]);
        // Create the poll
        $poll = Question::create([
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'question' => $validatedData['question'],
            'type' => $validatedData['type']
        ]);

        // Add options to the poll
        foreach ($validatedData['options'] as $optionData) {
            $option = new Option(['option_text' => $optionData['option_text']]);
            $poll->options()->save($option);
        }

        // Return a response, e.g., the poll with its options
        return response()->json($poll->load('options'), 201);
    }

    public function updatePoll(Question $question, Request $request)
    {

        // Validate the request data
        $validatedData = $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array',
            'start_date' => 'date|nullable',
            'end_date' => 'date|after:start_date|nullable',
            'type' => 'required|string|max:255',
            'options.*.option_text' => 'required|string|max:255'
        ]);
        // update the poll

        $question->update([
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'question' => $validatedData['question'],
            'type' => $validatedData['type']
        ]);

        // Get the IDs of the current options
        $currentOptionIds = $question->options->pluck('id')->toArray();

        // IDs of options that are still present
        $updatedOptionIds = [];

        foreach ($request->get('options') as $optionData) {
            if (isset($optionData['id'])) {
                // Update existing option
                Option::where('id', $optionData['id'])->update(['option_text' => $optionData['option_text']]);
                $updatedOptionIds[] = $optionData['id'];
            } else {
                // Create new option
                $newOption = $question->options()->create(['option_text' => $optionData['option_text']]);
                $updatedOptionIds[] = $newOption->id;
            }
        }

        // Delete options that were removed
        $optionsToDelete = array_diff($currentOptionIds, $updatedOptionIds);
        if (!empty($optionsToDelete)) {
            Option::whereIn('id', $optionsToDelete)->delete();
        }

        // Other update logic for the question itself

        return response()->json('Poll updated successfully', 200);
        // Return a response, e.g., the poll with its options
        return response()->json($question, 201);
    }

    public function deletePollWithOptionAndVotes(Question $question)
    {
        $question->options()->delete();
        $question->votes()->delete();
        $question->delete();

        return response()->json(null, 204);
    }
}

