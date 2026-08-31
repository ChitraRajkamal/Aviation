<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamCategory;
use App\Models\ExamEnroll;
use App\Models\ExamResult;
use App\Models\Qbank;
use App\Models\QbankQuestion;
use App\Models\Rating;
use App\Models\User;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Str;

class ExamController extends BaseController
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->input('filter')){
            $examList = Exam::query();
            if($request->search){
                $examList = $examList->where('title', 'LIKE', "%$request->search%");
            }
            if($request->category_id){
                $examList = $examList->where('exam_category_id', $request->category_id);
            }
        }else{
            $examList = Exam::query();
        }
        //$examList = $examList->where('organization_id', lms_organization_id());
        $examList = $examList->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        $categoriesHierarchy = ExamCategory::getHierarchy();
        
        return view('admin.exams.index', compact('examList', 'categoriesHierarchy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = ExamCategory::getHierarchy();
        $qbanks = Qbank::where([
            ['status', 1],
            ['organization_id', lms_organization_id()],
        ])->get();
        return view('admin.exams.create', compact('categoriesHierarchy', 'qbanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getExamAddRules(0, $request->exam_category_id),
            $this->getExamAddMessages()
        );
        $data['qbank_ids'] = array_values($data['qbank_ids']);
        $error = [];
        foreach ($data['qbank_ids'] as $qb) {
            $qbank_questions = QbankQuestion::where([
                ['qbank_id', $qb['id']],
                ['status', 1],
            ])->count();
            if($qb['questions'] > $qbank_questions){
                $error["qb_$qb[id]"] = "Insufficient question in this question bank<br>Only <b>$qbank_questions ACTIVE</b> questions are available";
            }
        }
        if ($error) {
            return redirect()->back()
                ->withErrors($error)
                ->withInput(); // Optional: keeps the old input
        }
        $data['slug'] = lms_uuid();
        $data['is_paid'] = $data['price'] > 0 ? 1 : 0;
        $data['status'] = 1;
        $data['description'] = $request->description ?? '';
        $exam = Exam::create($data);

        $metaData = $request->input('meta_data', []);
        $image = $request->file('meta_data.og_image');
        if($image){
            $metaData['og_image'] = $image->store("exams/$exam->id", 'public');
        }
        $exam->meta_data = $metaData;

        $image = $request->file('thumbnail');
        if($image){
            $exam->thumbnail = $image->store("exams/$exam->id", 'public');
        }

        // Update Slug
        $exam->slug = str()->slug($data['title'] . ' ' . $exam->id);
        $exam->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('Exam created')));
        }
        return to_route('admin.exams.index')->with('alert', generate_alert(__('Exam created')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        $this->getExam($exam->id);
        $categoriesHierarchy = ExamCategory::getHierarchy();
        $qbanks = Qbank::where([
            ['status', 1],
            ['organization_id', lms_organization_id()],
        ])->get();
        return view('admin.exams.edit', compact('exam', 'categoriesHierarchy', 'qbanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $this->getExam($exam->id);
        // Validation begins
        $data = $request->validate(
            $this->getExamAddRules($exam->id, $exam->exam_category_id),
            $this->getExamAddMessages()
        );

        $data['qbank_ids'] = array_values($data['qbank_ids']);
        $error = [];
        foreach ($data['qbank_ids'] as $qb) {
            $qbank_questions = QbankQuestion::where([
                ['qbank_id', $qb['id']],
                ['status', 1],
            ])->count();
            if($qb['questions'] > $qbank_questions){
                $error["qb_$qb[id]"] = "Insufficient question in this question bank<br>Only <b>$qbank_questions ACTIVE</b> questions are available";
            }
        }
        if ($error) {
            return redirect()->back()
                ->withErrors($error)
                ->withInput(); // Optional: keeps the old input
        }

        $metaData = $request->input('meta_data', []);
        $image = $request->file('meta_data.og_image');
        if($image){
            $metaData['og_image'] = $image->store("exams/$exam->id", 'public');
        }
        $data['meta_data'] = $metaData;

        $file = $request->file('thumbnail');
        if($file){
            $data['thumbnail'] = $file->store("exams/$exam->id", 'public');
        }

        $data['is_paid'] = $data['price'] > 0 ? 1 : 0;
        //$data['updated_by_id'] = lms_user_id();
        $exam->fill(array_merge(
            $data,
            ['description' => $request->description ?? '']
        ))->save();
        
        return to_route('admin.exams.edit', ['exam' => $exam])->with('alert', generate_alert(__('Exam updated')));
    }
    
    /**
     * Activate / Deactivate an exam.
     */
    public function activate($examId, $status)
    {
        $exam = $this->getExam($examId);
        if($exam){
            $exam->status = $status == 1 ? 1 : 0;
            $exam->save();
            return redirect()->back()->with('alert', generate_alert(__('Exam updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Exam not available'), 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam = $this->getExam($exam->id);
        try {
            $exam->delete();
            return redirect()->route('admin.exams.index')->with('alert', generate_alert(__('Exam deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    public function results(Request $request, $examId)
    {
        $exam = $this->getExam($examId);

        $filter = $request->input('f');
        $students = [];
        $where = [
            ['exam_id', $examId]
        ];
        if($filter && $filter['student_id']){
            $students = User::where('id', $filter['student_id'])->get();
            $where['user_id'] = $filter['student_id'];
        }
        $enrolls = ExamEnroll::where($where)->whereHas('user', function ($query){
            $query->where('organization_id', lms_organization_id());
        })->get();
        return view('admin.exams.results', compact('exam', 'enrolls', 'students'));
    }

    public function result_details($examId, $userId)
    {
        $exam = $this->getExam($examId);
        $enroll = ExamEnroll::where([
            ['exam_id', $examId],
            ['user_id', $userId]
        ])->first();
        if(!$enroll){
            abort(404, 'Exam not found');
        }
        $results = ExamResult::where([
            ['exam_id', $examId],
            ['user_id', $userId]
        ])->orderBy('created_at')->get();
        
        return view('admin.exams.result-details', compact('exam', 'enroll', 'results'));
    }

    public function answers($examId, $userId, $resultId)
    {
        $exam = $this->getExam($examId);
        $enroll = ExamEnroll::where([
            ['exam_id', $examId],
            ['user_id', $userId]
        ])->first();
        if(!$enroll){
            abort(404, 'Enroll not found');
        }
        $result = ExamResult::where([
            ['id', $resultId],
            ['exam_id', $examId],
            ['user_id', $userId]
        ])->first();
        if(!$result){
            abort(404, 'Result not found');
        }
        $answers = ExamAnswer::where([
            ['exam_id', $examId],
            ['user_id', $userId],
            ['exam_result_id', $resultId]
        ])->get();
        return view('admin.exams.answers', compact('exam', 'enroll', 'result', 'answers'));
    }

    public function ratings($examId){
        $exam = $this->getExam($examId);
        $ratings = Rating::where(column: [
            ['type', 'exam'],
            ['type_id', $examId],
            ['status', 1],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.exams.ratings', ['exam' => $exam, 'ratings' => $ratings]);
    }
}