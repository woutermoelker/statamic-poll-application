<?php

namespace App\Services;

use App\Models\Option;
use App\Models\Question;
use App\Models\Vote;

class VoteService
{


    public function registerVote(Question $question, Option $option, string $fingerprint)
    {
        if ($vote = $question->votes()->where('fingerprint', $fingerprint)->where('option_id', $option->id)->first()) {
            $vote->delete();

            return;
        }

        if ($question->type === 'radio') {
            $this->registerSingleVote($question, $option, $fingerprint);
        } else {
            $this->registerMultipleVote($question, $option, $fingerprint);
        }

    }

    private function registerSingleVote(Question $question, Option $option, string $fingerprint)
    {
        Vote::UpdateOrCreate([
            'question_id' => $question->id,
            'fingerprint' => $fingerprint
        ], [
            'option_id' => $option->id
        ]);
    }

    private function registerMultipleVote(Question $question, Option $option, string $fingerprint)
    {
        Vote::UpdateOrCreate([
            'question_id' => $question->id,
            'fingerprint' => $fingerprint,
            'option_id' => $option->id
        ]);
    }
}
