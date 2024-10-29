<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $table = 'datas';
    //protected $dates = ['created_at', 'updated_at'];
    public function getCreatedAttribute()
    {
        return $this->created_at->toDateTimeString();
    }
}
