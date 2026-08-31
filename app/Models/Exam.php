<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Exam extends BaseModel
{
    protected $casts = [
        'meta_data' => 'array', // Use 'object' if you prefer an stdClass object
        'qbank_ids' => 'array', // Use 'object' if you prefer an stdClass object
    ];
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(ExamCategory::class, 'exam_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function wishlist($userId = null)
    {
        return Wishlist::where([
            ['status', 1],
            ['user_id', $userId ?? auth()->id()],
            ['type', 'exam'],
            ['type_id', $this->id]
        ])->exists();
    }

    public function cart($userId = null)
    {
        return Cart::where([
            ['status', 1],
            ['user_id', $userId ?? auth()->id()],
            ['type', 'exam'],
            ['type_id', $this->id]
        ])->exists();
    }

    public function enroll($userId = null)
    {
        //return $this->belongsTo(ExamEnroll::class, 'exam_id');
        return ExamEnroll::where([
            ['exam_id', $this->id],
            ['user_id', $userId ?? auth()->id()],
            ['is_enrolled', true]
        ])->exists();
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class)->where('status', 1);
    }

    public function question_count()
    {
        return collect($this->qbank_ids)->sum('questions');
        //return $this->hasMany(ExamQuestion::class)->where('status', 1)->count();
    }

    public function added_marks()
    {
        return $this->hasMany(ExamQuestion::class)->where('status', 1)->sum('marks');
    }

    public function enroll_count()
    {
        return $this->hasMany(ExamEnroll::class)->where('is_enrolled', 1)->count();
    }

    public function getDurationTextAttribute($format = 'default')
    {
        if(!$this->duration){
            return '';
        }
        // Parse the duration (assuming it's in H:i:s format)
        $parts = explode(':', $this->duration);

        $hours = (int) $parts[0];
        $minutes = (int) $parts[1];
        $seconds = (int) $parts[2];

        if ($format === 'short') {
            return trim(($hours ? "{$hours}h " : '') . ($minutes ? "{$minutes}m " : '') . ($seconds ? "{$seconds}s" : ''));
        }

        $hourText = $hours > 0 ? "$hours " . Str::plural('hour', $hours) : '';
        $minuteText = $minutes > 0 ? "$minutes " . Str::plural('minute', $minutes) : '';
        $secondText = $seconds > 0 ? "$seconds " . Str::plural('second', $seconds) : '';

        return trim("$hourText $minuteText $secondText");
    }

    public function getIsEnrolledAttribute()
    {
        return ExamEnroll::where([
            ['user_id', auth()->id()],
            ['exam_id', $this->id],
            ['is_enrolled', true]
        ])->exists();
    }

    public function getIsAttendedAttribute()
    {
        return ExamEnroll::where([
            ['user_id', auth()->id()],
            ['exam_id', $this->id],
            ['is_enrolled', true],
            ['is_attended', true]
        ])->exists();
    }

    public function rating_average(bool $filterByOrg = false)
    {
        return lms_decimal_points(
            $this->hasMany(Rating::class, 'type_id')
                ->where([
                    ['status', 1],
                    ['type', 'exam'],
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
                ['type', 'exam'],
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
                ['type', 'exam'],
            ])
            ->when($filterByOrg, function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('organization_id', lms_organization_id());
                });
            })
            ->count();
    }
}
