<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    protected $fillable = [
        'name', 'brand', 'price', 'ram', 'storage', 'battery',
        'screen_size', 'camera', 'processor', 'os', 'image_url',
        'ram_mb', 'bluetooth', 'clock_speed', 'dual_sim', 'front_camera',
        'four_g', 'mobile_depth', 'mobile_wt', 'n_cores',
        'px_height', 'px_width', 'sc_h', 'sc_w',
        'talk_time', 'three_g', 'touch_screen', 'wifi',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
