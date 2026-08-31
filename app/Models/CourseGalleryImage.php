<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGalleryImage extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
