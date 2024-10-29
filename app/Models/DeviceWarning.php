<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class DeviceWarning extends Model
{
    use HasFactory;
    protected $table = 'device_warnings';

    public function warning(){
        return $this->belongsTo(Warning::class,'code','error_code')->select('_id','title','message','error_code');
    }

    public function deviceByMacId(){
        return $this->belongsTo(Device::class,'macid','macid')->select('_id','name');
    }

    public function company(){
        return $this->belongsTo(Company::class,'macid')->select('_id');
    }
}
