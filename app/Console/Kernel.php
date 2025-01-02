<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])->daily()->monitorName('Prune Scheduled Task Log Item');
        $schedule->command(RunHealthChecksCommand::class)->everyMinute()->monitorName('Run Health Checks');
        $schedule->command('backup:clean')->daily()->at('01:00')->timezone('America/New_York')->monitorName('Clean Backups');
        $schedule->command('backup:run')->daily()->at('01:30')->timezone('America/New_York')->monitorName('Run Backup');
        $schedule->command('sitemap:generate')->daily()->monitorName('Generate Sitemap');

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
