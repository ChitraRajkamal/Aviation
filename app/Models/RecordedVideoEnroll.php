<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordedVideoEnroll extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function recorded_video()
    {
        return $this->belongsTo(RecordedVideo::class, 'recorded_video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
