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
        $this->service->registerVote($question, $option, $request->fingerprint());
    }

}
