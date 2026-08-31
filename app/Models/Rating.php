<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getItemAttribute()
    {
        if($this->type == 'course'){
            $data = Course::find($this->type_id);
        }else if($this->type == 'exam'){
            $data = Exam::find($this->type_id);
        }else if($this->type == 'recorded-video'){
            $data = RecordedVideo::find($this->type_id);
        }else{
            $data = JobPost::find($this->type_id);
        }
        return $data;
    }

    public function getTimeDifferenceAttribute()
    {
        return 'dd';
    }
}
