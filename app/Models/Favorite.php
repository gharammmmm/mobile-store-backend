<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',    // <-- غيّرنا من session_id إلى user_id
        'mobile_id',
    ];

    public function mobile()
    {
        return $this->belongsTo(Mobile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
