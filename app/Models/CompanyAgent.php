<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class CompanyAgent extends Model
{
    use HasFactory;

    public function company(){
        return $this->belongsTo(Company::class,'company_id','_id')->select('_id','company_name');
    }
}
