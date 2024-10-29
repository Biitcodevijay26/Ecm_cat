<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
class CurrencyRate extends Model
{
    use HasFactory;
    protected $table = 'currency_rates';
}
