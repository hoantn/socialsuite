<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // [SOCIALSUITE][GPT] run every minute to publish scheduled posts
        $schedule->command('socialsuite:publish-due')->everyMinute();
    }
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
