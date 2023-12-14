<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Option;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $question1 = Question::create(['question' => 'Wat is je favoriete kleur?']);
        $question1->options()->createMany([
            ['option_text' => 'Rood', 'votes' => rand(1, 20)],
            ['option_text' => 'Blauw', 'votes' => rand(1, 20)],
            ['option_text' => 'Groen', 'votes' => rand(1, 20)]
        ]);

        // Tweede Poll
        $question2 = Question::create(['question' => 'Wat is je favoriete eten?']);
        $question2->options()->createMany([
            ['option_text' => 'Pizza', 'votes' => rand(1, 20)],
            ['option_text' => 'Sushi', 'votes' => rand(1, 20)],
            ['option_text' => 'Pasta', 'votes' => rand(1, 20)],
            ['option_text' => 'Pasta', 'votes' => rand(1, 20)]
        ]);
        // Derde Poll
        $question3 = Question::create(['question' => 'Wat is je favoriete seizoen?']);
        $question3->options()->createMany([
            ['option_text' => 'Lente', 'votes' => rand(1, 20)],
            ['option_text' => 'Zomer', 'votes' => rand(1, 20)],
            ['option_text' => 'Herfst', 'votes' => rand(1, 20)],
            ['option_text' => 'Winter', 'votes' => rand(1, 20)]
        ]);


        Question::factory(10)->create()->each(function ($question) {
            $options = Option::factory(rand(2, 5))->create([
                'question_id' => $question->id
            ]);

//            $options->each(function ($option) use ($question) {
//                Vote::factory(rand(0, 20))->create([
//                    'question_id' => $question->id,
//                    'option_id' => $option->id
//                ]);
//            });
        });
    }
}
