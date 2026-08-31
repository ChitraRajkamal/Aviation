<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnroll;
use App\Models\Exam;
use App\Models\ExamEnroll;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class DashboardController extends Controller
{
    public function admin()
    {
        $organizationId = lms_organization_id();
        if(lms_is_organization()){
            $counter = [
                'courses' => Course::where('organization_id', $organizationId)->count(),
                'exams' => Exam::where('organization_id', $organizationId)->count(),
                'students' => User::where('organization_id', $organizationId)->where('role', 'student')->count(),
                'staffs' => User::where('organization_id', $organizationId)->where('role', 'organization')->count(),
                'course_enrolls' => CourseEnroll::where('organization_id', $organizationId)->count(),
                'exam_enrolls' => ExamEnroll::where('organization_id', $organizationId)->count(),
            ];
            $course_enrolls = CourseEnroll::where('organization_id', $organizationId)->orderBy('id', 'desc')->limit(5)->get();
            $exam_enrolls = ExamEnroll::where('organization_id', $organizationId)->orderBy('id', 'desc')->limit(5)->get();
            return view('admin.organization-dashboard', compact('counter', 'course_enrolls', 'exam_enrolls'));
        }else{            
            $counter = [
                'courses' => Course::where('organization_id', $organizationId)->count(),
                'exams' => Exam::where('organization_id', $organizationId)->count(),
                'students' => User::where('organization_id', $organizationId)->where('role', 'student')->count(),
                'organizations' => Organization::count(),
                'course_enrolls' => CourseEnroll::where('organization_id', $organizationId)->count(),
                'exam_enrolls' => ExamEnroll::where('organization_id', $organizationId)->count(),
            ];
            $course_enrolls = CourseEnroll::where('organization_id', $organizationId)->orderBy('id', 'desc')->limit(5)->get();
            $exam_enrolls = ExamEnroll::where('organization_id', $organizationId)->orderBy('id', 'desc')->limit(5)->get();
            return view('admin.dashboard', compact('counter', 'course_enrolls', 'exam_enrolls'));
        }
    }
}
