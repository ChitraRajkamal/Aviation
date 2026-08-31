<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function answers_all()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_result_id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_result_id')->where('user_id', request()->user()->id)
            ->select(['id', 'exam_result_id', 'answer', 'is_correct', 'created_at', 'updated_at']); // DO NOT REMOVE "exam_result_id", it is needed
    }
}
