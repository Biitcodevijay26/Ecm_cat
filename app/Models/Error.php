<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Error extends Model
{
    use HasFactory;
    protected $table = 'error';

    public function device(){
        return $this->belongsTo(Device::class,'device_id','_id')->select('_id','name');
    }
}
