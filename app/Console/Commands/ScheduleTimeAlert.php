<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\ScheduledTimeAlert;
use Exception;
use Illuminate\Console\Command;
use Log;

class ScheduleTimeAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:schedule-time {--alert_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule time alert for admin only';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $alert_config = ScheduledTimeAlert::find($this->option('alert_id'));

            $alert              = new Alert();
            $alert->account_id  = $alert_config->account_id;
            $alert->name        = $alert_config->name;
            $alert->description = '';
            $alert->type        = Alert::ALERT_TYPE_SCHEDULED;
            $alert->save();
        } catch (Exception $e) {
            Log::error('Failed to create alert from config: ' . $alert_config->id . " --- " . (string)$e);
        }
        Log::info('Created alert: ' . $alert->id);
    }
}
