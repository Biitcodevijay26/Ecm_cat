<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';

    public function user(){
        return $this->belongsTo(User::class,'user_id','_id')->select('_id','first_name','last_name','full_name');
    }
}
