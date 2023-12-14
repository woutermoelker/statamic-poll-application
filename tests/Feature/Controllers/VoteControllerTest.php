<?php

namespace Tests\Feature\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Services\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoteControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function test_can_access_vote_endpoint()
    {
        $question = Question::factory()
            ->hasOptions(1)
            ->create();

        $option = $question->options->first();

        $response = $this->post(route('vote.store', [$question, $option]));

        $response->assertStatus(200);
    }

    public function test_vote_service_is_called()
    {
        $service = new class extends VoteService {
            public function registerVote(Question $question, Option $option, string $fingerprint)
            {
                $this->called = true;
            }

            public function isCalled(): bool
            {
                return $this->called;
            }

        };

        $this->app->bind(VoteService::class, fn() => $service);

        $question = Question::factory()
            ->hasOptions(1)
            ->create();

        $option = $question->options->first();

        $response = $this->post(route('vote.store', [$question, $option]));

        $this->assertTrue($service->isCalled());
    }

}
