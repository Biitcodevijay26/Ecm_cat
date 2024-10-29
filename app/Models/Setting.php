<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
}
