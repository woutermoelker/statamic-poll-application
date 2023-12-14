<?php

namespace App\Console;

use App\Models\Question;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // Task to show questions
        $schedule->call(function () {
            Question::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->update(['is_visible' => true]);
        })->everyMinute(); // Adjust the frequency as needed

        // Task to hide questions
        $schedule->call(function () {
            Question::where('end_date', '<', now())
                ->update(['is_visible' => false]);
        })->everyMinute(); // Adjust the frequency as needed
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
