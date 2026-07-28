<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'mobile_id',
        'reviewer_name',
        'rating',
        'comment',
    ];

    public function mobile()
    {
        return $this->belongsTo(Mobile::class);
    }
}
