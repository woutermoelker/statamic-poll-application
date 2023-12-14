<?php

namespace App\Services;

use App\Enums\VoteAction;
use App\Models\Option;
use App\Models\Question;
use App\Models\Vote;

class VoteService
{


    public function registerVote(Question $question, Option $option, string $fingerprint): VoteAction
    {
        if ($vote = $question->votes()->where('fingerprint', $fingerprint)->where('option_id', $option->id)->first()) {
            $vote->delete();

            return VoteAction::REMOVED;
        }

        if ($question->type === 'radio') {
            $this->registerSingleVote($question, $option, $fingerprint);
        } else {
            $this->registerMultipleVote($question, $option, $fingerprint);
        }

        return VoteAction::REGISTERED;
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
