<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

class Lesson extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function is_completed()
    {
        return CourseEnroll::whereJsonContains('lesson_ids', $this->id)->where([
            ['user_id', auth()->id()],
            ['course_id', $this->course_id]
        ])->exists();
    }
    
    public function questions($check_status = false)
    {
        $data = $this->hasMany(Question::class);
        if($check_status){
            $data->where('status', 1);
        }
        return $data;
    }
    
    public function question_count($check_status = false)
    {
        $data = $this->hasMany(Question::class);
        if($check_status){
            $data->where('status', 1);
        }
        return $data->count();
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

        if ($format === 'short') {
            return trim(($hours ? "{$hours}h " : '') . ($minutes ? "{$minutes}m" : ''));
        }

        $hourText = $hours > 0 ? "$hours " . Str::plural('hour', $hours) : '';
        $minuteText = $minutes > 0 ? "$minutes " . Str::plural('minute', $minutes) : '';

        return trim("$hourText $minuteText");
    }

    public static function getDurationSum($course_id)
    {
        $durations = Lesson::where([
            ['course_id', $course_id],
            ['is_quiz', 0],
            ['duration', '!=', null],
            ['status', 1]
        ])->pluck(('duration'));

        // Initialize a Carbon instance at zero
        $totalDuration = Carbon::createFromTime(0, 0, 0);

        foreach ($durations as $duration) {
            $time = Carbon::createFromFormat('H:i:s', $duration);
            $totalDuration->addHours($time->hour)
                        ->addMinutes($time->minute)
                        ->addSeconds($time->second);
        }

        $hours = (int) $totalDuration->format('H');
        $minutes = (int) $totalDuration->format('i');
        $seconds = (int) $totalDuration->format('s');
        $output = "";
        if($hours){
            $output .= "$hours " . lms_plural('hour', $hours) . ' ';
        }
        if($minutes){
            $output .= "$minutes " . lms_plural('minute', $minutes) . ' ';
        }
        if($seconds){
            $output .= "$seconds " . lms_plural('second', $seconds) . ' ';
        }
        return $output;
    }
}
