<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

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
}
