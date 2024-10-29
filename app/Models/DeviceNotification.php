<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class DeviceNotification extends Model
{
    use HasFactory;
    protected $table = 'device_notifications';


    public function notification(){
        return $this->belongsTo(Error::class,'code','error_code')->select('_id','title','message','error_code');
        // many to one relation is here 
    }

    public function device() {
        return $this->belongsTo(Device::class, 'macid', 'macid'); // Assuming 'macid' is the linking field
    }
    

}
