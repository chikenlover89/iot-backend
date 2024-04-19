<?php

namespace App\Console\Commands;

use App\Models\Peripheral;
use App\Models\PeripheralData;
use App\Models\RawDeviceData;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Log;

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
        $entries   = RawDeviceData::where('is_processed', false)->get();
        $processed = 0;

        foreach ($entries as $entry) {
            try {
                $json = json_decode($entry->data, true);

                foreach ($json as $key => $value) {
                    if (!Peripheral::isValidParameterId($key)) {
                        Log::error('Failed to process RawDeviceData: ' . $entry->id . ', key:' . $key);

                        continue;
                    }

                    $peripheral = Peripheral::findOrCreate($entry->device_id, $key);

                    Model::unguard();
                    PeripheralData::create([
                        'peripheral_id' => $peripheral->id,
                        'value'         => $value,
                    ]);
                    Model::reguard();
                }

                $entry->is_processed = true;
                $entry->save();

                $processed++;
            } catch (Exception $e) {
                Log::error('Failed to process RawDeviceData: ' . $entry->id . " --- " . (string)$e);
            }
        }

        Log::info('Processed RawDeviceData ' . $processed . '/' . count($entries));
    }
}
