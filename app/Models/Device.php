<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = 'inverters';

    public function company(){
        return $this->belongsTo(Company::class,'company_id','_id')->select('_id','company_name');
    }

    public function cluster(){
        return $this->belongsTo(Cluster::class,'cluster_id','_id')->select('_id','name');
    }
}
