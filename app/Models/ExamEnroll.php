<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamEnroll extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function attempt_count()
    {
        return ExamResult::where('exam_id', $this->exam_id)->where('user_id', $this->user_id)->count();
    }

    public function attempts()
    {
        return $this->hasMany(ExamResult::class, 'exam_id', 'exam_id')->where('user_id', request()->user()->id);
        //return ExamResult::where('exam_id', $this->exam_id)->where('user_id', $this->user_id)->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
