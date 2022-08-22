<?php

namespace App\Console;

use App\Repositories\ChannexRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $channexRepository = new ChannexRepository();
            \Log::info("Processing booking revisions");
            $channexRepository->processBookingRevisions();
        })->everyFifteenMinutes();

        $schedule->command('command:remove-old-periods')
            ->dailyAt('23:30');
        $schedule->command('command:sync-invoices')
            ->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
