<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnroll;
use App\Models\Exam;
use App\Models\ExamEnroll;
use App\Models\ExamQuestion;
use App\Models\JobPost;
use App\Models\JobPostEnroll;
use App\Models\OrganizationStudentUsage;
use App\Models\RecordedVideo;
use App\Models\RecordedVideoEnroll;

class BaseController extends Controller
{
    public int $userId = 0;
    public int $organizationId = 0;
    public bool $isOrganization = false;

    public function __construct()
    {
        $this->userId = auth()->id() ?? 0;
        $this->organizationId = auth()->user()->organization->id ?? 0;
        $this->isOrganization = $this->organizationId > 0;
    }
    
    public function getExamBySlug($slug, $abort = true)
    {
        $exam = Exam::where([
            ['status', 1],
            ['slug', $slug]
        ])->first();
        if ($abort && !$exam) {
            abort(404, 'Exam not found');
        }
        return $exam;
    }
    
    public function isEnrolledExam($exam, $abort = true)
    {
        $examEnrolled = ExamEnroll::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id],
            ['is_enrolled', true]
        ])->exists();
        if($abort && !$examEnrolled){
            abort(404, 'Exam not found');
        }
        return $examEnrolled;
    }
    
    public function isAttendedExam($exam, $abort = false)
    {
        $examAttended = ExamEnroll::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id],
            ['is_enrolled', true],
            ['is_attended', true]
        ])->exists();
        if($abort && !$examAttended){
            abort(404, 'Exam not found');
        }
        return $examAttended;
    }
    
    public function getTotalQuestionCount($examId)
    {
        return $total_questions = ExamQuestion::where([
            ['exam_id', $examId],
            ['status', 1],
        ])->count();
    }
    
    public function getCourseBySlug($slug, $abort = true)
    {
        $course = Course::where([
            ['status', 'Active'],
            ['slug', $slug]
        ])->first();
        if ($abort && !$course) {
            abort(404, 'Course not found');
        }
        return $course;
    }
    
    public function isEnrolledCourse($course, $abort = true)
    {
        $isEnrolled = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id],
            ['is_enrolled', true]
        ])->exists();
        if($abort && !$isEnrolled){
            abort(404, 'Course not found');
        }
        return $isEnrolled;
    }
    
    public function isCompletedCourse($course, $abort = true)
    {
        $isCompleted = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id],
            ['is_completed', true]
        ])->exists();
        if($abort && !$isCompleted){
            abort(404, 'Course not found');
        }
        return $isCompleted;
    }
    
    public function getRecordedVideoBySlug($slug, $abort = true)
    {
        $course = RecordedVideo::where([
            ['status', 1],
            ['slug', $slug]
        ])->first();
        if ($abort && !$course) {
            abort(404, 'Recorded Video not found');
        }
        return $course;
    }
    
    public function isEnrolledRecordedVideo($exam, $abort = true)
    {
        $isEnrolled = RecordedVideoEnroll::where([
            ['user_id', $this->userId],
            ['recorded_video_id', $exam->id],
            ['is_enrolled', true]
        ])->exists();
        if($abort && !$isEnrolled){
            abort(404, 'Recorded Video not found');
        }
        return $isEnrolled;
    }
    
    public function getJobPostBySlug($slug, $abort = true)
    {
        $course = JobPost::where([
            ['status', 1],
            ['slug', $slug]
        ])->first();
        if ($abort && !$course) {
            abort(404, 'Job Post not found');
        }
        return $course;
    }
    
    public function isEnrolledJobPost($exam, $abort = true)
    {
        $isEnrolled = JobPostEnroll::where([
            ['user_id', $this->userId],
            ['job_post_id', $exam->id],
            ['is_enrolled', true]
        ])->exists();
        if($abort && !$isEnrolled){
            abort(404, 'Job Post not found');
        }
        return $isEnrolled;
    }
    
    /*public function isAttendedCourse($course, $abort = false)
    {
        $courseAttended = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id],
            ['is_enrolled', true],
            ['is_attended', true]
        ])->exists();
        if($abort && !$courseAttended){
            abort(404, 'Course not found');
        }
        return $courseAttended;
    }*/
    
    public function autoEnrollCheck($type, $type_id, $type_category_id)
    {
        $organizationData = auth()->user()->organization ?? false;
        if($organizationData){
            $no_of_students = $organizationData->no_of_students;
            $permissions = lms_organization_permissions($organizationData)[$type] ?? false;
            if($permissions){
                $usage = OrganizationStudentUsage::where([
                    ['organization_id', $this->organizationId],
                ])->count();
                $used = OrganizationStudentUsage::where([
                    ['organization_id', $this->organizationId],
                    ['user_id', $this->userId]
                ])->exists();
                if($no_of_students > $usage && !$used){
                    if(in_array($type_id, ($permissions['items'] ?? [])) || in_array($type_category_id, ($permissions['categories'] ?? []))){
                        OrganizationStudentUsage::create([
                            'organization_id' => $this->organizationId,
                            'user_id' => $this->userId,
                        ]);
                        return true;
                    }
                }else if ($used) {
                    return in_array($type_id, ($permissions['items'] ?? [])) || in_array($type_category_id, ($permissions['categories'] ?? []));
                }
            }
        }
        return false;
    }
}
