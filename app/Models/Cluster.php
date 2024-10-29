<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;
    protected $table = 'cluster';


    public function device(){
        return $this->hasMany(Device::class,'cluster_id','_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','_id')->select('_id','company_name');
    }
}
