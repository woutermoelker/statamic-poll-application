<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Services\VoteService;
use FFMpeg\FFMpeg;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
class QuestionController extends Controller
{

    // question crud
    public function index()
    {
        $questions = Question::all();
        return view('admin.questions.index', compact('questions'));
    }

    public function show(Question $question): JsonResponse
    {
        $question->load('options');

        return response()->json([
            'question' => $question
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'question' => 'required',
        ]);

        $question = new Question([
            'question' => $request->get('question'),
            'type' => $request->get('type'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ]);
        $question->save();
        return json_encode(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
        ]);

        $question = Question::find($id);
        $question->question = $request->get('question');
        $question->type = $request->get('type');
        $question->start_date = $request->get('start_date');
        $question->end_date = $request->get('end_date');
        $question->save();
        return json_encode(['success' => true]);
    }

    public function delete($id)
    {
        $question = Question::find($id);
        $question->delete();
        return json_encode(['success' => true]);
    }


    public function uploadMedia(Request $request, $questionId)
    {
        $question = Question::findOrFail($questionId);

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

            $question->video_path = 'converted_videos/' . $video->hashName();
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');

            $image = Image::make(storage_path('app/public/' . $imagePath))
                ->encode('webp', 90)
                ->save(storage_path('app/public/converted_images/' . $image->hashName() . '.webp'));

            $question->image_path = 'converted_images/' . $image->hashName() . '.webp';
        }

        $question->save();

        return response()->json(['message' => 'Media uploaded successfully']);
    }

}
