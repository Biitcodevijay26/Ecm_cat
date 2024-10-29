<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Warning extends Model
{
    use HasFactory;
    protected $table = 'warning';

    public function device(){
        return $this->belongsTo(Device::class,'device_id','_id')->select('_id','name');
    }
}
