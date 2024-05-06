<?php

namespace App\Console;

use App\Models\ScheduledTimeAlert;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('process:device-data')->everyThirtySeconds();

        $scheduled_alerts = ScheduledTimeAlert::all();
        foreach ($scheduled_alerts as $alert) {
            $schedule->command(
                'alert:schedule-time --alert_id=' . $alert->id
            )->cron($alert->cron);
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
