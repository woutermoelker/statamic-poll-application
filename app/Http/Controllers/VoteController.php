<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Services\VoteService;
use Illuminate\Http\Request;

class VoteController extends Controller
{

    public function __construct(private readonly VoteService $service)
    {

    }

    public function store(Request $request, Question $question, Option $option)
    {
        $action = $this->service->registerVote($question, $option, $request->fingerprint());

        return response()->json([
            'question_id' => $question->id,
            'option_id' => $option->id,
            'action' => $action,
            'selected_options' => $question->votes()
                ->where('fingerprint', $request->fingerprint())
                ->select('option_id')
                ->pluck('option_id')
                ->toArray()
        ]);
    }

}
