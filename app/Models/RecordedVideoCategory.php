<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordedVideoCategory extends Model
{
    use HasFactory;

    protected $table = 'recorded_video_categories';
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function children()
    {
        return $this->hasMany(RecordedVideoCategory::class, 'parent_id');
    }

    public function recorded_videos()
    {
        return $this->hasMany(RecordedVideo::class, 'recorded_video_category_id');
    }

    public function recorded_video_count()
    {
        return $this->hasMany(RecordedVideo::class, 'recorded_video_category_id')->where('organization_id', lms_organization_id())->count();
    }
}
