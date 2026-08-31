<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function section()
    {
        return $this->belongsTo(Lesson::class);
    }
}
