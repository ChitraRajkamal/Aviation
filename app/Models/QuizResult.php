<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function answers_all()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_result_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_result_id')->where('user_id', request()->user()->id)
            ->select(['id', 'quiz_result_id', 'answer', 'is_correct', 'created_at', 'updated_at']); // DO NOT REMOVE "quiz_result_id", it is needed
    }
}
