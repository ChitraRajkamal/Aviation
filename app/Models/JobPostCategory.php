<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostCategory extends Model
{
    use HasFactory;

    protected $table = 'job_post_categories';
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function job_posts()
    {
        return $this->hasMany(JobPost::class, 'job_post_category_id');
    }

    public function job_post_count()
    {
        return $this->hasMany(JobPost::class, 'job_post_category_id')->where('organization_id', lms_organization_id())->count();
    }
}
