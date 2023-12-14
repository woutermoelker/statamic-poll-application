<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PollController extends Controller
{
    public function getQuestionsWithOptions()
    {
        $currentDate = Carbon::now();

        $questions = Question::with('options')->orderBy('votes_count', 'desc')
            ->get();

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


        $request->validate([
            'video' => 'nullable|file|mimetypes:video/*',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoPath = $video->store('videos', 'public');

            FFMpeg::fromDisk('public')
                ->open($videoPath)
                ->export()
                ->toDisk('public')
                ->inFormat(new \FFMpeg\Format\Video\X264)
                ->save('converted_videos/' . $video->hashName());

            $poll->media_path = 'converted_videos/' . $video->hashName();
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');

            // Generate a unique filename for the image
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();

            $image = Image::make(storage_path('app/public/' . $imagePath))
                ->encode('webp', 90)
                ->save(storage_path('app/public/converted_images/' . $imageName));

            $poll->media_path = 'converted_images/' . $imageName;
        }


        $poll->save();


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
    }

    public function deletePollWithOptionAndVotes(Question $question)
    {
        $question->options()->delete();
        $question->votes()->delete();

        if ($question->media_path && Storage::disk('public')->exists($question->media_path)) {
            Storage::disk('public')->delete($question->media_path);
        }

        $question->delete();

        return response()->json(null, 204);
    }
}

