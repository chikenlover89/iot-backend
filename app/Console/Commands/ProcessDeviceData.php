<?php

namespace App\Console\Commands;

use App\Models\RawDeviceData;
use Illuminate\Console\Command;

class ProcessDeviceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:device-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process raw device data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rawData = RawDeviceData::where('is_processed', false)->get();

        foreach ($rawData as $data) {
            $json = json_decode($data->data, true);
            
            // ...

            $data->is_processed = true;
            $data->save();

            $this->info('Processed data for device: ' . $data->device_id);
        }
    }
}
