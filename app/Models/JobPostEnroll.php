<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPostEnroll extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function job_post()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
