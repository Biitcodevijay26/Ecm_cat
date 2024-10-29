<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class IconSetting extends Model
{
    use HasFactory;
    protected $table = 'icon_settings';

    protected $appends = [
        'icon_img_url',
    ];

    public function getIconImgUrlAttribute()
    {
        return $this->icon_name ? url('theme-asset/images/overview-icons/'.$this->icon_name) : '';
    }
}
