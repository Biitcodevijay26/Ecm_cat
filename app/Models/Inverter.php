<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Inverter extends Model
{
    use HasFactory;
    protected $table = 'inverters';
    public function getStatusTextAttribute()
    {
        if($this->status == 0){
            return 'Inactive';
        } else if($this->status == 1){
            return 'Active';
        } else {
            return '';
        }
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id_str')->select('_id','name');
    }
}
