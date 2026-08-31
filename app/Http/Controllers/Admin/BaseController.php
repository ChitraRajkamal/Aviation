<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\JobPost;
use App\Models\Qbank;
use App\Models\RecordedVideo;
use App\Models\Subscription;

class BaseController extends Controller
{
    public function getExam($id, $abort = true)
    {
        $exam = Exam::where([
            ['id', $id],
            //['organization_id', lms_organization_id()]
        ])->first();
        if ($abort && !$exam) {
            abort(404, 'Exam not found');
        }
        return $exam;
    }
    
    public function getCourse($id, $abort = true)
    {
        $course = Course::where([
            ['id', $id],
            //['organization_id', lms_organization_id()]
        ])->first();
        if ($abort && !$course) {
            abort(404, 'Course not found');
        }
        return $course;
    }

    public function getQbank($id, $abort = true)
    {
        $exam = Qbank::where([
            ['id', $id],
            ['organization_id', lms_organization_id()]
        ])->first();
        if ($abort && !$exam) {
            abort(404, 'Question bank not found');
        }
        return $exam;
    }
    
    public function getJobPost($id, $abort = true)
    {
        $jobPost = JobPost::where([
            ['id', $id],
            //['organization_id', lms_organization_id()]
        ])->first();
        if ($abort && !$jobPost) {
            abort(404, 'Job Post not found');
        }
        return $jobPost;
    }
    
    public function getRecordedVideo($id, $abort = true)
    {
        $recordedVideo = RecordedVideo::where([
            ['id', $id],
            //['organization_id', lms_organization_id()]
        ])->first();
        if ($abort && !$recordedVideo) {
            abort(404, 'Recorded Video not found');
        }
        return $recordedVideo;
    }
    
    public function getSubscription($id, $abort = true)
    {
        $jobPost = Subscription::where([
            ['id', $id],
        ])->first();
        if ($abort && !$jobPost) {
            abort(404, 'Subscription not found');
        }
        return $jobPost;
    }
}
