<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'country';
}
