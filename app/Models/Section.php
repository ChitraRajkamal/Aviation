<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'title',
        'course_id',
        'user_id',
        'status'
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_by', 'asc');
    }

    public function lesson_count()
    {
        return $this->hasMany(Lesson::class)->where('is_quiz', 0)->count();
    }

    public function quiz_count()
    {
        return $this->hasMany(Lesson::class)->where('is_quiz', 1)->count();
    }
}
