<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class CompanyChannel extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class,'company_id','_id')->select('_id','company_name');
    }

    public function device(){
        return $this->hasMany(Device::class,'company_id','company_id')->select('_id','name');
    }
}
