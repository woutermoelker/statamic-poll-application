<?php

namespace Tests\Feature\Services;

use App\Models\Question;
use App\Services\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoteServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */


   public function test_votes_count_is_incremented()
   {
         $question = Question::factory()
              ->hasOptions(1)
              ->create();

         $option = $question->options->first();

         $service = new VoteService();

         $service->registerVote($question, $option, 'fingerprint');

         $this->assertEquals(1, $option->votes()->count());
   }

    public function test_question_with_radio_can_only_have_one_vote_per_fingerprint()
    {
        $question = Question::factory()
            ->hasOptions(2)
            ->create([
                'type' => 'radio'
            ]);

        $option1 = $question->options->first();
        $option2 = $question->options->last();

        $service = new VoteService();

        $service->registerVote($question, $option1, 'fingerprint');
        $service->registerVote($question, $option2, 'fingerprint');


        $this->assertEquals(1, $option2->votes()->count());
        $this->assertEquals(0, $option1->votes()->count());


    }

    public function test_question_with_checkbox_can_have_multiple_votes_per_fingerprint()
    {
        $question = Question::factory()
            ->hasOptions(2)
            ->create([
                'type' => 'checkbox'
            ]);

        $option1 = $question->options->first();
        $option2 = $question->options->last();

        $service = new VoteService();

        $service->registerVote($question, $option1, 'fingerprint');
        $service->registerVote($question, $option2, 'fingerprint');

        $this->assertEquals(1, $option2->votes()->count());
        $this->assertEquals(1, $option1->votes()->count());
    }
}
