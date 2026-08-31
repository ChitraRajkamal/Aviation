<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(ExamCategory::class, 'exam_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
