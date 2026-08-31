<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends BaseModel
{
    protected $casts = [
        'meta_data' => 'array', // Use 'object' if you prefer an stdClass object
    ];
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wishlist($userId = null)
    {
        return Wishlist::where([
            ['status', 1],
            ['user_id', $userId ?? auth()->id()],
            ['type', 'course'],
            ['type_id', $this->id]
        ])->exists();
    }

    public function cart($userId = null)
    {
        return Cart::where([
            ['status', 1],
            ['user_id', $userId ?? auth()->id()],
            ['type', 'course'],
            ['type_id', $this->id]
        ])->exists();
    }

    public function enroll($userId = null)
    {
        return CourseEnroll::where([
            ['course_id', $this->id],
            ['user_id', $userId ?? auth()->id()],
            ['is_enrolled', true]
        ])->exists();
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_by', 'asc');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function lesson_count()
    {
        return $this->hasMany(Lesson::class)->where([
            ['status', 1],
            ['is_quiz', 0]
        ])->count();
    }

    public function enroll_count()
    {
        return $this->hasMany(CourseEnroll::class)->count();
    }

    public function quiz_count()
    {
        return $this->hasMany(Lesson::class)->where([
            ['status', 1],
            ['is_quiz', 1]
        ])->count();
    }

    public function lesson_quiz_count()
    {
        return $this->hasMany(Lesson::class)->where([
            ['status', 1]
        ])->count();
    }

    public function rating_average(bool $filterByOrg = false)
    {
        return lms_decimal_points(
            $this->hasMany(Rating::class, 'type_id')
                ->where([
                    ['status', 1],
                    ['type', 'course'],
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
                ['type', 'course'],
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
                ['type', 'course'],
            ])
            ->when($filterByOrg, function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('organization_id', lms_organization_id());
                });
            })
            ->count();
    }

    public function galleryImages()
    {
        return $this->hasMany(CourseGalleryImage::class)
            ->orderBy('sort_order');
    }
}
