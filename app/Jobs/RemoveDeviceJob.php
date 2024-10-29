<?php

namespace App\Jobs;

use App\Models\Device;
use App\Models\DeviceWarning;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemoveDeviceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("Start Delete Device Jobs");
        $data = $this->data;
        if(isset($data['device_id']) && $data['device_id']){
            $device = Device::where('_id',$data['device_id'])->first();
            if($device){
                $macid = $device->macid ?? '';
                if($macid){
                    $device_wrninig = DeviceWarning::where('macid',"7c:9e:bd:e3:36:14")->delete();
                    echo "<pre>"; print_r($device_wrninig); exit("CALL Jobs");
                }

            }
        }
        \Log::info("End Delete Device Jobs");
    }
}
