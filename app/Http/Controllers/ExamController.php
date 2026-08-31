<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamCategory;
use App\Models\ExamEnroll;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\OrganizationSetting;
use App\Models\Payment;
use App\Models\Qbank;
use App\Models\QbankQuestion;
use App\Models\Rating;
use DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Mail;
use Storage;

class ExamController extends BaseController
{
    protected Exam $exam;
    public function __construct(Request $request)
    {
        parent::__construct();
        /*$currentAction = Route::currentRouteAction();
        list($currentController, $currentMethod) = explode('@', $currentAction);
        $controller = explode('\\', $currentController);
        $controller = end($controller);*/
        $abort = true;
        $slug = $request->slug ?? '';
        if($slug){
            $this->exam = $this->getExamBySlug($slug, $abort);
        }        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewType = $request->cookie('lms_cl_vt') ?? 'grid';
        $filter = ['query' => '', 'category' => [], 'is_paid' => []];
        if($request->search){
            $filter['query'] = $request->input('query');
            $filter['category'] = $request->input('category');
            $filter['is_paid'] = $request->input('is_paid');
            $where = [
                ['status', 1]
            ];
            if($filter['query']){
                $where []= ['title', 'LIKE', "%$filter[query]%"];
            }
            if(!$filter['is_paid']){
                $filter['is_paid'] = [];
            }
            if(!$filter['category']){
                $filter['category'] = [];
            }
            $exams = Exam::query()->where($where )->whereHas('category', function ($query) {
                $query->where('status', 1);
            });
            if (!empty($filter['category']) && is_array($filter['category'])) {
                $categoryIds = ExamCategory::getAllCategoryIds($filter['category']);        
                $exams->whereIn('exam_category_id', $categoryIds);
            }
            if($filter['is_paid'] && is_array($filter['is_paid'])){
                $exams->whereIn('is_paid', $filter['is_paid']);
            }
            $exams = $exams->orderBy('id', 'desc')
                ->paginate(lms_setting('exam_pagination_size'))
                ->appends($request->query());
        }else{
            $exams = Exam::query()
                ->where('status', 1)
                ->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('exam_pagination_size'));
        }

        $categories = ExamCategory::getCategoriesWithExams();
        $counts = collect([
            'is_paid' => [0, 1],
        ])->flatMap(function ($values, $key) {
            return collect($values)->mapWithKeys(function ($value) use ($key) {
                return ["{$key}_{$value}" => Exam::where([
                    ['status', 1],
                    [$key, $value],
                ])->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })->count()];
            });
        })->toArray();
        
        return view('frontend.exams.index', compact('exams', 'categories', 'viewType', 'filter', 'counts'));
    }

    public function details($slug)
    {
        //session(['ttest' => 'value']);
        $exam = $this->exam;
        
        $examEnrolled = $this->isEnrolledExam($exam, false);
        $examAttended = $this->isAttendedExam($exam, false);
        if(!$examEnrolled){
            if($this->autoEnrollCheck('exams', $exam->id, $exam->exam_category_id)){
                ExamEnroll::create([
                    'user_id' => $this->userId,
                    'exam_id' => $exam->id,
                    'is_enrolled' => true,
                    'organization_id' => $exam->organization_id,
                ]);
                $examEnrolled = true;
                DB::table('cart') ->where('type', 'exam') ->where('type_id', $exam->id) ->delete();
            }
        }

        $rated = Rating::where([
            ['user_id', $this->userId],
            ['type', 'exam'],
            ['type_id', $exam->id],
            ['status', 1]
        ])->exists();
        $ratings = Rating::where([
            ['type', 'exam'],
            ['type_id', $exam->id],
            ['status', 1]
        ])->orderByDesc('id')->get();
        $averageRating = $ratings->avg('rating');

        $ratingCounts = Rating::where([
            ['type', 'exam'],
            ['type_id', $exam->id],
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
        
        return view('frontend.exams.details', compact('exam', 'examEnrolled', 'examAttended', 'rated', 'ratingPercentages', 'ratingCounts', 'ratings', 'averageRating'));
    }

    public function prepare($slug)
    {
        $exam = $this->exam;
        return view('frontend.exams.prepare', compact('exam'));
    }

    public function create_order($slug)
    {
        $exam = $this->exam;
        if(!$exam->is_paid){
            return redirect(lms_exam_slug($exam))->with('alert', generate_alert(__('This is free exam. No payment needed'), 'danger'));
        }
        $check = ExamEnroll::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id]
        ])->exists();
        if(!$check){
            $oldPayment = Payment::where([
                ['user_id', $this->userId],
                ['type', 'exam'],
                ['type_id', $exam->id],
                ['status', 'pending']
            ])->first();
            if($oldPayment){
                return to_route('exams.buy', ['slug' => $slug, 'paymentId' => $oldPayment->id]);
            }
            
            $response = lms_create_razorpay_order('INR', $exam->price);
            if($response == null){
                return  redirect()->back()->with('alert', generate_alert(__('Error contacting razorpay'), 'danger'));
            }
            //$response = '{"amount":5000,"amount_due":5000,"amount_paid":0,"attempts":0,"created_at":1738320247,"currency":"INR","entity":"order","id":"order_Pq1Q0QyHmIYuIq","notes":{"notes_key_1":""},"offer_id":null,"receipt":"4c26dccf-eee5-4ab1-8cff-e4d5a21ee55b","status":"created"}';
            //$response = json_decode($response);

            $payment = [
                'user_id' => $this->userId,
                'type' => 'exam',
                'type_id' => $exam->id,
                'date' => now(),
                'amount' => $exam->price,
                'order_id' => $response->id,
                'status' => 'pending',
                'source' => 'website',
                'request_data' => json_encode($response),
            ];
            $new = Payment::create($payment);
        }
        return to_route('exams.buy', ['slug' => $slug, 'paymentId' => $new->id]);
    }

    public function buy($slug, $paymentId)
    {
        $exam = $this->exam;
        $payment = Payment::find($paymentId);
        if(!$payment || $payment->status == 'captured'){
            abort(404, 'payment not found');
        }
        return view('frontend.exams.buy', compact('exam', 'payment'));
    }

    public function attend($slug)
    {
        $exam = $this->exam;
        $examEnrolled = $this->isEnrolledExam($exam);
        $attended_count = ExamResult::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id]
        ])->count();
        if($exam->retake != 0 && $attended_count >= $exam->retake){
            return redirect(lms_exam_slug($exam))->with('alert', generate_alert(__('No more reattempt is allowed'), 'danger'));
        }
        $qbank_questions = [];
        $question_count = 0;
        foreach ($exam->qbank_ids as $qb) {
            $qb_id = $qb['id'];
            $current_qbank_questions = ['id' => $qb_id, 'marks' => $qb['marks']];
            $current_qbank_questions['qbank'] = Qbank::find($qb_id);
            $current_questions = QbankQuestion::where([
                ['status', 1],
                ['qbank_id', $qb_id]
            ])->whereNotIn('id', function($query){
                $query->select('qbank_question_id')
                    ->from((new ExamAnswer)->getTable())->where('user_id', $this->userId);
            })->inRandomOrder()->take($qb['questions'])->get();
            $current_count = $current_questions->count();
            $question_count += $current_count;
            if($qb['questions'] != $current_count){
                $remaining = $qb['questions'] - $current_count;
                $current_questions = $current_questions->merge(QbankQuestion::where([
                    ['status', 1],
                    ['qbank_id', $qb_id]
                ])->whereNotIn('id', array_column($current_questions->toArray(), 'id'))
                ->inRandomOrder()->take($remaining)->get());
                $question_count += $current_questions->count();
            }
            $current_qbank_questions['questions'] = $current_questions;
            $qbank_questions []= $current_qbank_questions;
        }
        
        if(!$question_count){
            return redirect(lms_exam_slug($exam))->with('alert', generate_alert(__('No questions available in this exam'), 'danger'));
        }
        return view('frontend.exams.attend', compact('exam', 'question_count', 'qbank_questions'));
    }

    public function save_answers($slug, Request $request)
    {
        $userId = $this->userId;
        $time_taken_seconds = (int) $request->input('time_taken');
        $time_taken = gmdate('H:i:s', $time_taken_seconds);
        
        $answers = $request->input('answer');
        $exam = $this->exam;
        
        $this->isEnrolledExam($exam);

        $attended_count = ExamResult::where([
            ['user_id', $userId],
            ['exam_id', $exam->id]
        ])->count();
        if($exam->retake != 0 && $attended_count >= $exam->retake){
            return redirect(lms_exam_slug($exam))->with('alert', generate_alert(__('No more reattempt is allowed'), 'danger'));
        }
        
        $qbank_ids = $exam->qbank_ids;
        $qbank_marks = array_column($qbank_ids, 'marks', 'id');
        
        $obtained_mark = $attempted = $correct_answers = $wrong_answers = 0;
        $bulk_answers = [];
        
        foreach ($answers as $qbank_question_id => $ans) {
            if(!isset($ans['answer'])) $ans['answer'] = '';
            if(is_string($ans['answer'])) $ans['answer'] = trim($ans['answer']);
            if($ans['answer'] !== ''){
                $attempted++;
            }
            // Do not forget to make changes in API controller
            $qbankQuestion = QbankQuestion::select('id', 'qbank_id', 'type', 'title', 'options', 'answer', 'description', 'marks')->find($qbank_question_id);
            if(!$qbankQuestion) continue;

            $qbankQuestion->marks = (float) $qbank_marks[$qbankQuestion->qbank_id];
            $data = [];
            $data['user_id'] = $userId;
            $data['exam_id'] = $exam->id;
            $data['qbank_id'] = $qbankQuestion->qbank_id;
            $data['qbank_question_id'] = $qbank_question_id;
            $data['answer'] = is_array($ans['answer']) ? json_encode($ans['answer']) : trim($ans['answer']);
            $data['is_correct'] = false;
            if($qbankQuestion->type == 'multiple'){
                if(is_array($ans['answer'])) $data['answer'] = implode(config('constants.ANSWER_DELIMITER'), $ans['answer']);
            }
            $earned_marks = 0;
            if($qbankQuestion->answer == $data['answer']) {
                $data['is_correct'] = true;
                $obtained_mark += $qbankQuestion->marks;
                $earned_marks = $qbankQuestion->marks;
                $correct_answers++;
            }else if($ans['answer']){
                $obtained_mark -= $exam->negative_mark;
                $earned_marks = -1 * $exam->negative_mark;
                $wrong_answers++;
            }
            $qbankQuestion = $qbankQuestion->toArray();
            $qbankQuestion['actual_marks'] = $qbankQuestion['marks'];
            $qbankQuestion['marks'] = $earned_marks;
            $data['question'] = json_encode($qbankQuestion);
            $bulk_answers []= $data;
        }

        $total_questions = $exam->question_count();
        $percentage = 0;
        $is_pass = false;
        if($obtained_mark > 0){
            $percentage = $obtained_mark * (100 / $exam->total_mark);
            $is_pass = $obtained_mark >= $exam->pass_mark;
        }
        $result = [];
        $result['user_id'] = $userId;
        $result['exam_id'] = $exam->id;
        $result['total_questions'] = $total_questions;
        $result['total_mark'] = $exam->total_mark;
        $result['pass_mark'] = $exam->pass_mark;
        $result['obtained_mark'] = $obtained_mark;
        $result['negative_mark'] = $exam->negative_mark;
        $result['percentage'] = $percentage;
        $result['attempted'] = $attempted;
        $result['correct'] = $correct_answers;
        $result['wrong'] = $wrong_answers;
        $result['time_taken'] = $time_taken;
        $result['is_pass'] = $is_pass;
        $exam_result = ExamResult::create($result);

        foreach ($bulk_answers as $a) {
            $a['exam_result_id'] = $exam_result->id; 
            ExamAnswer::create($a); 
        }
        
        if(!$this->isAttendedExam($exam, false)){
            $examEnroll = ExamEnroll::where([
                ['user_id', $this->userId],
                ['exam_id', $exam->id]
            ])->first();
            if($examEnroll){
                $examEnroll->is_attended = true;
                $examEnroll->save();
            }
        }

        foreach ($qbank_ids as $qb) {
            $attended_count = ExamAnswer::where([
                ['user_id', $userId],
                ['qbank_id', $qb['id']],
                ['is_fully_attended', false]
            ])->count('qbank_question_id');
            $qbank_question_count = QbankQuestion::where('qbank_id', $qb['id'])->count();
            if($attended_count == $qbank_question_count){
                ExamAnswer::where([
                    ['user_id', $userId],
                    ['qbank_id', $qb['id']],
                    ['is_fully_attended', false]
                ])->update(['is_fully_attended' => true]);
            }
        }
        
        return redirect(lms_exam_slug($exam, 'result'));
    }

    public function result($slug)
    {
        $exam = $this->exam;
        $this->isAttendedExam($exam, true);
        $results = ExamResult::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id]
        ])->get();
        return view('frontend.exams.result', compact('exam', 'results'));
    }

    public function result_details($slug, $id)
    {
        $exam = $this->exam;
        $examEnrolled = $this->isAttendedExam($exam, true);
        $result = ExamResult::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id],
            ['id', $id]
        ])->first();
        if (!$result) {
            abort(404, 'Result not found');
        }
        $answers = ExamAnswer::where([
            ['exam_id', $exam->id],
            ['exam_result_id', $result->id]
        ])->get();
        return view('frontend.exams.result-details', compact('exam', 'result', 'answers'));
    }

    /**
     * Enroll into an exam.
     */
    public function enroll($slug)
    {
        $exam = $this->exam;
        if($exam->is_paid){
            return redirect(lms_exam_slug($exam))->with('alert', generate_alert(__('This is not free exam. Please make payment'), 'danger'));
        }
        $check = ExamEnroll::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id]
        ])->exists();
        if(!$check){
            ExamEnroll::create([
                'user_id' => $this->userId,
                'exam_id' => $exam->id,
                'is_enrolled' => true,
                'organization_id' => $exam->organization_id,
            ]);
        }
        return redirect(lms_exam_slug($exam))->with('alert', generate_alert(__('Enrollment is successful')));
    }

    public function exam_certificate($slug, $action){
        $exam = $this->exam;
        $examCompleted = ExamEnroll::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id],
            ['is_attended', true]
        ])->first();
        if(!$examCompleted){
            abort(404);
        }
        if(!$examCompleted){
            return redirect()->back()->with('alert', generate_alert(__('You did not complete the exam'), 'danger'));
        }
        $bestPercentage = ExamResult::where([
            ['user_id', $this->userId],
            ['exam_id', $exam->id]
        ])->max('percentage');
        $grade = lms_calculate_grade($bestPercentage);
        $user = auth()->user();
        $organization = $user->organization;
        $template = lms_setting('exam_certificate_template');
        $data = [
            //...lms_settings('certificate'),
            'logo' => lms_setting('certificate_logo_image'),
            'signature' => lms_setting('certificate_signature_image'),
            'title' => lms_setting('certificate_title'),
            'student_name' => $user->full_name,
            'exam_name' => $exam->title,
            'grade' => $grade,
            'date' => lms_format_date($examCompleted->updated_at, 'D, d M Y'),
        ];
        if($organization){
            $organizationSetting = OrganizationSetting::where('organization_id', $organization->id)->first();
            $data['logo'] = $organizationSetting->certificate_logo;
            $data['signature'] = $organizationSetting->certificate_signature;
            $data['title'] = $organizationSetting->exam_certificate_title;
            $template = $organizationSetting->exam_certificate_template;
        }
        //return view("frontend.certificate.exam-$template", $data);
        $pdf = Pdf::loadView("frontend/certificate/exam-$template", $data);
    
        $uniqueId = lms_uuid();
        $certificatePath = "certificates/exams/{$uniqueId}-{$exam->id}-{$user->id}.pdf";
        Storage::disk('public')->put($certificatePath, $pdf->output());
        if($action == 'download'){
            return redirect(lms_storage($certificatePath));
        }else{
            //$user->email = 'hussainmh39@gmail.com';
            if(!$user->email) abort(404);
            $subject = 'Exam Certificate';
            $content = 'Hi, ' . $user->full_name . '<br>Your exam certificate is ready and it is attached in this email. Please check it';
            
            Mail::html($content, function ($message) use ($user, $subject, $certificatePath) {
                $message->to($user->email)
                    ->subject($subject)
                    ->attach('storage/' . $certificatePath, [
                        'as' => 'exam-certificate.pdf',
                        'mime' => 'application/pdf',
                    ]);
            });

            return redirect()->back()->with('alert', generate_alert(__('Certificate has been sent to your Email ID')));
        }
    }
}
