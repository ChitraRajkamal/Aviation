<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnroll;
use App\Models\Lesson;
use App\Models\OrganizationSetting;
use App\Models\Payment;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizResult;
use App\Models\Rating;
use App\Models\Section;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use Mail;
use Storage;

class CourseController extends BaseController
{
    protected Course $course;
    public function __construct(Request $request)
    {
        parent::__construct();
        $abort = true;
        $slug = $request->slug ?? '';
        if($slug){
            $this->course = $this->getCourseBySlug($slug, $abort);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewType = $request->cookie('lms_cl_vt') ?? 'grid';
        $filter = ['query' => '', 'category' => [], 'level' => [], 'is_paid' => []];
        if($request->search){
            $filter['query'] = $request->input('query');
            $filter['category'] = $request->input('category');
            $filter['level'] = $request->input('level');
            $filter['is_paid'] = $request->input('is_paid');
            $where = [
                ['status', 'Active']
            ];
            if($filter['query']){
                $where []= ['title', 'LIKE', "%$filter[query]%"];
            }
            if(!$filter['level']){
                $filter['level'] = [];
            }
            if(!$filter['is_paid']){
                $filter['is_paid'] = [];
            }
            if(!$filter['category']){
                $filter['category'] = [];
            }
            $courses = Course::query()->where($where )->whereHas('category', function ($query) {
                $query->where('status', 1);
            });
            if (!empty($filter['category']) && is_array($filter['category'])) {
                $categoryIds = CourseCategory::getAllCategoryIds($filter['category']);        
                $courses->whereIn('course_category_id', $categoryIds);
            }
            if($filter['level'] && is_array($filter['level'])){
                $courses->whereIn('level', $filter['level']);
            }
            if($filter['is_paid'] && is_array($filter['is_paid'])){
                $courses->whereIn('is_paid', $filter['is_paid']);
            }
            $courses = $courses->orderBy('id', 'desc')
                ->paginate(lms_setting('course_pagination_size'))
                ->appends($request->query());
        }else{
            $courses = Course::query()
                ->where('status', 'Active')
                ->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('course_pagination_size'));
        }

        $categories = CourseCategory::getCategoriesWithCourses();
        /*$counts = [
            'level_Beginner' => Course::where([
                ['status', 'Active'],
                ['level', 'Beginner']
            ])->count(),
            'level_Intermediate' => Course::where([
                ['status', 'Active'],
                ['level', 'Intermediate']
            ])->count(),
            'level_Advanced' => Course::where([
                ['status', 'Active'],
                ['level', 'Advanced']
            ])->count(),
            'is_paid_0' => Course::where([
                ['status', 'Active'],
                ['is_paid', 0]
            ])->count(),
            'is_paid_1' => Course::where([
                ['status', 'Active'],
                ['is_paid', 1]
            ])->count(),
        ];*/
        $counts = collect([
            'level' => ['Beginner', 'Intermediate', 'Advanced'],
            'is_paid' => [0, 1],
        ])->flatMap(function ($values, $key) {
            return collect($values)->mapWithKeys(function ($value) use ($key) {
                return ["{$key}_{$value}" => Course::where([
                    ['status', 'Active'],
                    [$key, $value],
                ])->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })->count()];
            });
        })->toArray();
        
        return view('frontend.courses.index', compact('courses', 'categories', 'viewType', 'filter', 'counts'));
    }

    public function details($slug)
    {
        $course = $this->course;
        $courseEnrolled = $this->isEnrolledCourse($course, false);
        $courseCompleted = $this->isCompletedCourse($course, false);
        if(!$courseEnrolled){
            if($this->autoEnrollCheck('courses', $course->id, $course->course_category_id)){
                CourseEnroll::create([
                    'user_id' => $this->userId,
                    'course_id' => $course->id,
                    'is_enrolled' => true,
                    'enrolled_date' => now(),
                    'organization_id' => $course->organization_id,
                    'lesson_ids' => [],
                ]);
                $courseEnrolled = true;
                DB::table('cart') ->where('type', 'course') ->where('type_id', $course->id) ->delete();
            }
        }

        $rated = Rating::where([
            ['user_id', $this->userId],
            ['type', 'course'],
            ['type_id', $course->id],
            ['status', 1]
        ])->exists();
        $ratings = Rating::where([
            ['type', 'course'],
            ['type_id', $course->id],
            ['status', 1]
        ])->orderByDesc('created_at')->get();
        $averageRating = $ratings->avg('rating');

        $ratingCounts = Rating::where([
            ['type', 'course'],
            ['type_id', $course->id],
            ['status', 1]
        ])->selectRaw('rating, COUNT(*) as count')
          ->groupBy('rating')
          ->pluck('count', 'rating')
          ->toArray();
          
        $ratingCounts = array_replace(array_fill(1, 5, 0), $ratingCounts);

        $totalRatings = $ratings->count();        
        $reverseRatingCounts = array_reverse($ratingCounts, true);
        $ratingPercentages = array_map(function ($count) use ($totalRatings) {
            return $totalRatings > 0 ? round(($count / $totalRatings) * 100, 2) : 0;
        }, $reverseRatingCounts);

        $lessonDurations = Lesson::getDurationSum($course->id);
        return view('frontend.courses.details', compact('course', 'lessonDurations', 'courseEnrolled', 'courseCompleted', 'rated', 'ratingPercentages', 'ratingCounts', 'ratings', 'averageRating'));
    }

    public function create_order($slug)
    {
        $course = $this->course;
        if(!$course->is_paid){
            return redirect(lms_course_slug($course))->with('alert', generate_alert(__('This is free course. No payment needed'), 'danger'));
        }
        $check = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id]
        ])->exists();
        if(!$check){
            $oldPayment = Payment::where([
                ['user_id', $this->userId],
                ['type', 'course'],
                ['type_id', $course->id],
                ['status', 'pending']
            ])->first();
            if($oldPayment){
                return to_route('courses.buy', ['slug' => $slug, 'paymentId' => $oldPayment->id]);
            }
            $price = $course->discount_flag ? $course->discounted_price : $course->price;
            $response = lms_create_razorpay_order('INR', $price, 'course');
            if(isset($response->error) && $response->error->description){
                return redirect(lms_course_slug($course))->with('alert', generate_alert(__($response->error->description), 'danger'));
            }

            $payment = [
                'user_id' => $this->userId,
                'type' => 'course',
                'type_id' => $course->id,
                'date' => now(),
                'amount' => $price,
                'order_id' => $response->id,
                'status' => 'pending',
                'source' => 'website',
                'request_data' => json_encode($response),
            ];
            $new = Payment::create($payment);
        }
        return to_route('courses.buy', ['slug' => $slug, 'paymentId' => $new->id]);
    }

    public function buy($slug, $paymentId)
    {
        $course = $this->course;
        $payment = Payment::where([
            ['type', 'course'],
            ['id', $paymentId]
        ])->first();
        if(!$payment || $payment->status == 'captured'){
            abort(404, 'payment not found');
        }
        $lessonDurations = Lesson::getDurationSum($course->id);
        return view('frontend.courses.buy', compact('course', 'payment', 'lessonDurations'));
    }

    public function enroll(Request $request, $slug)
    {
        $course = $this->course;
        $courseEnrolled = $this->isEnrolledCourse($course, false);
        if(!$courseEnrolled){
            CourseEnroll::create([
                'user_id' => $this->userId,
                'course_id' => $course->id,
                'is_enrolled' => true,
                'enrolled_date' => now(),
                'organization_id' => $course->organization_id,
                'lesson_ids' => [],
            ]);
        }
        return redirect(lms_course_slug($course))->with('alert', generate_alert(__('Course enrolled successfully')));   
    }

    public function course_certificate($slug, $action){
        $course = $this->course;
        $courseCompleted = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id],
            ['is_completed', true]
        ])->first();
        if(!$courseCompleted){
            abort(404);
        }
        if(!$courseCompleted){
            return redirect()->back()->with('alert', generate_alert(__('You did not complete the course'), 'danger'));
        }
        $user = auth()->user();
        $organization = $user->organization;
        $template = lms_setting('course_certificate_template');
        $data = [
            //...lms_settings('certificate'),
            'logo' => lms_setting('certificate_logo_image'),
            'signature' => lms_setting('certificate_signature_image'),
            'title' => lms_setting('certificate_title'),
            'student_name' => $user->full_name,
            'course_name' => $course->title,
            'date' => lms_format_date($courseCompleted->completed_date, 'D, d M Y'),
        ];
        if($organization){
            $organizationSetting = OrganizationSetting::where('organization_id', $organization->id)->first();
            $data['logo'] = $organizationSetting->certificate_logo;
            $data['signature'] = $organizationSetting->certificate_signature;
            $data['title'] = $organizationSetting->course_certificate_title;
            $template = $organizationSetting->course_certificate_template;
        }
        //exit(file_get_contents(base_path('resources/views/frontend/certificate/course.blade.php')));
        $pdf = Pdf::loadView("frontend/certificate/course-$template", $data);
    
        $uniqueId = lms_uuid();
        $certificatePath = "certificates/courses/{$uniqueId}-{$course->id}-{$user->id}.pdf";
        Storage::disk('public')->put($certificatePath, $pdf->output());
        if($action == 'download'){
            return redirect(lms_storage($certificatePath));
        }else{
            //$user->email = 'hussainmh39@gmail.com';
            if(!$user->email) abort(404);
            $subject = 'Course Completion Certificate';
            $content = 'Hi, ' . $user->full_name . '<br>Your course completion certificate is ready and it is attached in this email. Please check it';
            
            Mail::html($content, function ($message) use ($user, $subject, $certificatePath) {
                $message->to($user->email)
                    ->subject($subject)
                    ->attach('storage/' . $certificatePath, [
                        'as' => 'course-completion-certificate.pdf',
                        'mime' => 'application/pdf',
                    ]);
            });

            return redirect()->back()->with('alert', generate_alert(__('Certificate has been sent to your Email ID')));
        }
    }

    public function course_stage(Request $request, $slug, $lessonId = 0)
    {
        $course = $this->course;
        $lessonId = (int) $lessonId;
        $courseEnrolled = $this->isEnrolledCourse($course);

        $enroll = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id]
        ])->first();

        $currentLesson = [];
        if($enroll->current_lesson_id){
            $lessonId = $lessonId ? $lessonId : $enroll->current_lesson_id;
            if(!in_array($lessonId, $enroll->lesson_ids)){
                return to_route('courses.stage', ['slug' => $course->slug]);
            }
            $currentLesson = Lesson::where([
                ['status', 1],
                ['course_id', $course->id],
                ['id', $lessonId]
            ])->first();
        }else{ 
            // First time
            $firstSection = Section::where([
                ['status', 1],
                ['course_id', $course->id]
            ])->select(['id', 'title'])->orderBy('sort_by')->orderBy('id')->limit(1)->first();
            if($firstSection){
                $currentLesson = Lesson::where([
                    ['status', 1],
                    ['course_id', $course->id]
                ])->orderBy('sort_by')->orderBy('id')->limit(1)->first();
                if($currentLesson){
                    $lessonIds = array_merge($enroll->lesson_ids ?? [], [$currentLesson->id]);
                    $enroll->lesson_ids = $lessonIds;
                    $enroll->current_lesson_id = $currentLesson->id;
                    $enroll->save();
                }                
            }
        }
        if(!$currentLesson){
            return redirect(lms_course_slug($course));
        }
        $questions = [];
        if($request->input('start') == 'true' && $lessonId){
            $questions = Question::where([
                ['status', 1],
                ['course_id', $course->id],
                ['lesson_id', $lessonId]
            ])->select(['id', 'title', 'type', 'options', 'description', 'answer', 'marks'])->get();
            if(count($questions) == 0){
                return to_route('courses.stage', ['slug' => $course->slug, 'lessonId' => $lessonId])->with('alert', generate_alert(__('No questions available in this quiz'), 'danger'));
            }
        }        

        $results = QuizResult::where([
            ['user_id', $this->userId],
            ['course_id', $course->id],
            ['lesson_id', $lessonId]
        ])->get();
        
        if($request->input('start') == 'true' && ($currentLesson->retake != 0 && count($results) >= $currentLesson->retake)){
            return to_route('courses.stage', ['slug' => $course->slug, 'lessonId' => $lessonId])->with('alert', generate_alert(__('No more reattempt is allowed'), 'danger'));
        }

        return view('frontend.courses.stage', compact('course', 'courseEnrolled', 'currentLesson', 'questions', 'results', 'enroll'));
    }

    public function save_answers($slug, $lessonId, Request $request)
    {
        $userId = $this->userId;
        $time_taken_seconds = (int) $request->input('time_taken');
        $time_taken = gmdate('H:i:s', $time_taken_seconds);
        
        $answers = $request->input('answer');
        $course = $this->course;
        $lessonId = (int) $lessonId;
        $courseEnrolled = $this->isEnrolledCourse($course);

        $lesson = Lesson::where([
            ['status', 1],
            ['course_id', $course->id],
            ['id', $lessonId]
        ])->first();
        
        $courseId = $course->id;
        $attended_count = QuizResult::where([
            ['user_id', $userId],
            ['course_id', $courseId],
            ['lesson_id', $lessonId]
        ])->count();

        if($lesson->retake != 0 && $attended_count >= $lesson->retake){
            return to_route('courses.stage', ['slug' => $course->slug, 'lessonId' => $lessonId])->with('alert', generate_alert(__('No more reattempt is allowed'), 'danger'));
        }

        $obtained_mark = $attempted = 0;
        $bulk_answers = [];
        foreach ($answers as $quiz_question_id => $ans) {
            if(!isset($ans['answer'])) $ans['answer'] = '';
            if(is_string($ans['answer'])) $ans['answer'] = trim($ans['answer']);
            if($ans['answer']){
                $attempted++;
            }
            // Do not forget to make chang
            // Do not forget to make changes in API controller
            $quizQuestion = Question::select('id', 'course_id', 'lesson_id', 'type', 'title', 'options', 'answer', 'description', 'marks')->find($quiz_question_id);
            if(!$quizQuestion) continue;
            
            $data = [];
            $data['user_id'] = $userId;
            $data['course_id'] = $courseId;
            $data['lesson_id'] = $lessonId;
            $data['question_id'] = $quiz_question_id;
            $data['answer'] = is_array($ans['answer']) ? json_encode($ans['answer']) : trim($ans['answer']);
            $data['question'] = json_encode($quizQuestion);
            $data['is_correct'] = false;
            if($quizQuestion->type == 'multiple'){
                if(is_array($ans['answer'])) $data['answer'] = implode(config('constants.ANSWER_DELIMITER'), $ans['answer']);
            }
            if($quizQuestion->answer == $data['answer']) {
                $data['is_correct'] = true;
                $obtained_mark += $quizQuestion->marks;
            }
            $bulk_answers []= $data;
        }
        $total_questions = Question::where([
            ['course_id', $courseId],
            ['lesson_id', $lessonId],
            ['status', 1],
        ])->count();
        $percentage = 0;
        $is_pass = false;
        if($obtained_mark > 0){
            $percentage = $obtained_mark * (100 / $lesson->total_mark);
            $is_pass = $obtained_mark >= $lesson->pass_mark;
        }
        $result = [];
        $result['user_id'] = $userId;
        $result['course_id'] = $courseId;
        $result['lesson_id'] = $lessonId;
        $result['total_questions'] = $total_questions;
        $result['total_mark'] = $lesson->total_mark;
        $result['pass_mark'] = $lesson->pass_mark;
        $result['obtained_mark'] = $obtained_mark;
        $result['percentage'] = $percentage;
        $result['attempted'] = $attempted;
        $result['time_taken'] = $time_taken;
        $result['is_pass'] = $is_pass;
        $quiz_result = QuizResult::create($result);

        foreach ($bulk_answers as $a) {
            $a['quiz_result_id'] = $quiz_result->id; 
            QuizAnswer::create($a);
        }

        return to_route('courses.stage', ['slug' => $course->slug, 'lessonId' => $lessonId])->with('alert', generate_alert(__('Quiz completed')));
    }

    public function next_lesson($slug, $lessonId, Request $request)
    {
        $course = $this->course;
        $allSections = Section::where([
            ['status', 1],
            ['course_id', $course->id]
        ])->select('id')->orderBy('sort_by')->orderBy('id')->get();
        $allLessonIds = [];
        foreach ($allSections as $k => $section) {
            $allSectionLessons = Lesson::where([
                ['status', 1],
                ['course_id', $course->id],
                ['section_id', $section->id]
            ])->select('id')->orderBy('sort_by')->orderBy('id')->get();
            $allLessonIds = array_merge($allLessonIds, array_column($allSectionLessons->toArray(), 'id'));
        }
        $lessonId = (int) $lessonId;

        processAgain:
        $enroll = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $course->id],
            ['is_enrolled', true]
        ])->first();

        if($enroll->current_lesson_id){
            if(in_array($lessonId, $enroll->lesson_ids)){
                $g_index = array_search($lessonId, $allLessonIds);
                $index = array_search($lessonId, $enroll->lesson_ids);
                
                /*if($g_index != $index){
                    $lessonIds = $enroll->lesson_ids;
                    foreach ($lessonIds as $c_index => $c_value) {
                        if($c_index >= $index) unset($lessonIds[$c_index]);
                    }
                    $lessonIds = array_values($lessonIds);
                    //echo "allLessonIds : $g_index -- " . implode(',', $allLessonIds);
                    //echo "<br>LessonIds : $index -- " . implode(',', $lessonIds);
                    $enroll->lesson_ids = $lessonIds;
                    $enroll->save();
                    goto processAgain;
                }*/
                if(isset($enroll->lesson_ids[$index+1])){
                    $newNextLessonId = $enroll->lesson_ids[$index+1];
                    $enroll->current_lesson_id = $newNextLessonId;
                    $enroll->save();
                    return response()->json(['status' => 'success', 'id' => $newNextLessonId]);
                }
            }
            $newNextLessonId = $lessonId;
            $lessonId = $enroll->current_lesson_id;
            $currentLesson = Lesson::where([
                ['status', 1],
                ['course_id', $course->id],
                ['id', $lessonId]
            ])->first();
            $nextLessons = Lesson::where([
                ['status', 1],
                ['section_id', $currentLesson->section_id],
                ['course_id', $course->id]
            ])->select('id')->orderBy('sort_by')->orderBy('id')->get();
            $nextLessonIds = array_column($nextLessons->toArray(), 'id');
            $index = array_search($lessonId, $nextLessonIds);
            
            if(isset($nextLessonIds[$index+1])){
                $enroll = CourseEnroll::where([
                    ['user_id', $this->userId],
                    ['course_id', $course->id]
                ])->first();
                $newNextLessonId = $nextLessonIds[$index+1];
                $enroll->current_lesson_id = $newNextLessonId;
                if(!in_array($newNextLessonId, $enroll->lesson_ids)){
                    $lessonIds = array_merge($enroll->lesson_ids, [$newNextLessonId]);
                    $enroll->lesson_ids = $lessonIds;
                }
                $enroll->save();
            }else{
                $sectionId = $currentLesson->section_id;
                $nextSections = Section::where([
                    ['status', 1],
                    ['course_id', $course->id]
                ])->select('id')->orderBy('sort_by')->orderBy('id')->get();
                $nextSectionIds = array_column($nextSections->toArray(), 'id');
                $index = array_search($sectionId, $nextSectionIds);
                if(isset($nextSectionIds[$index+1])){
                    $newNextSectionId = $nextSectionIds[$index+1];
                    $newNextLesson = Lesson::where([
                        ['status', 1],
                        ['course_id', $course->id],
                        ['section_id', $newNextSectionId]
                    ])->orderBy('sort_by')->orderBy('id')->limit(1)->first();
                    if($newNextLesson){
                        $newNextLessonId = $newNextLesson->id;
                        $lessonIds = array_merge($enroll->lesson_ids, [$newNextLessonId]);
                        $enroll->current_lesson_id = $newNextLessonId;
                        $enroll->lesson_ids = $lessonIds;
                        $enroll->save();
                    }                    
                }else{
                    $enroll->completed_date = now();
                    $enroll->is_completed = true;
                    $enroll->save();
                }
            }
        }
        return response()->json(['status' => 'success', 'id' => $newNextLessonId]);
    }
}
