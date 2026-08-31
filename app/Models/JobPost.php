<?php

namespace App\Models;

class JobPost extends BaseModel
{
    protected $casts = [
        'meta_data' => 'array',
    ];
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(JobPostCategory::class, 'job_post_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function enroll($userId = null)
    {
        return JobPostEnroll::where([
            ['job_post_id', $this->id],
            ['user_id', $userId ?? auth()->id()],
            ['is_enrolled', true]
        ])->exists();
    }

    public function enroll_count()
    {
        return $this->hasMany(JobPostEnroll::class)->where('is_enrolled', 1)->count();
    }

    public function cart($userId = null)
    {
        return Cart::where([
            ['status', 1],
            ['user_id', $userId ?? auth()->id()],
            ['type', 'job-post'],
            ['type_id', $this->id]
        ])->exists();
    }

    public function rating_average(bool $filterByOrg = false)
    {
        return lms_decimal_points(
            $this->hasMany(Rating::class, 'type_id')
                ->where([
                    ['status', 1],
                    ['type', 'job-post'],
                ])
                ->when($filterByOrg, function ($q) {
                    $q->whereHas('user', function ($query) {
                        $query->where('organization_id', lms_organization_id());
                    });
                })
                ->avg('rating')
        );
    }

    public function ratings(bool $filterByOrg = false)
    {
        return $this->hasMany(Rating::class, 'type_id')
            ->where([
                ['status', 1],
                ['type', 'job-post'],
            ])
            ->when($filterByOrg, function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('organization_id', lms_organization_id());
                });
            });
    }

    public function rating_count(bool $filterByOrg = false)
    {
        return $this->hasMany(Rating::class, 'type_id')
            ->where([
                ['status', 1],
                ['type', 'job-post'],
            ])
            ->when($filterByOrg, function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('organization_id', lms_organization_id());
                });
            })
            ->count();
    }
}
