<?php

namespace App\Console;

use App\Console\Commands\DeleteOlderDocArchiveCommand;
use App\Console\Commands\PruneOrderAuditsCommand;
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
        DeleteOlderDocArchiveCommand::class,
        PruneOrderAuditsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('docArchive:delete')->dailyAt('19:00');
        $schedule->command('audits:pune')->dailyAt('19:00');
        $schedule->command('telescope:prune --hours=96')->daily();
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
