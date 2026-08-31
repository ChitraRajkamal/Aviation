<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnroll extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    protected $casts = [
        'lesson_ids' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /*public function attempt_count()
    {
        return CourseResult::where('course_id', $this->course_id)->where('user_id', $this->user_id)->count();
    }

    public function attempts()
    {
        return $this->hasMany(CourseResult::class, 'course_id', 'course_id')->where('user_id', request()->user()->id);
    }*/

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
