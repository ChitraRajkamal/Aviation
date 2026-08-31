<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnroll;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuizResult;
use App\Models\Rating;
use App\Models\Section;
use App\Models\User;
use App\Models\CourseGalleryImage;
use App\Traits\ValidationsTrait;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Str;
use Validator;

class CourseController extends BaseController
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courseList = Course::query();
        if($request->search){
            $courseList = $courseList->where('title', 'LIKE', "%$request->search%");
        }
        //$courseList = $courseList->where('organization_id', lms_organization_id());
        $courseList = $courseList
                        ->orderBy('id', 'desc')
                        ->paginate(lms_setting('admin_pagination_size'))
                        ->appends($request->query());
        
        return view('admin.courses.index', compact('courseList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = CourseCategory::getHierarchy();
        return view('admin.courses.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getCourseAddRules(0, $request->course_category_id),
            $this->getCourseAddMessages()
        );
        $data['short_description'] = $data['short_description'] ?? '';
        $data['slug'] = lms_uuid();
        $data['user_id'] = $request->user()->id;
        $data['description'] = $request->description;
        $data['organization_id'] = lms_organization_id();
        $data['created_by_id'] = lms_user_id();
        $course = Course::create($data);

        $image = $request->file('thumbnail');
        if($image){
            $course->thumbnail = $image->store("courses/$course->id", 'public');
        }

        // Update Slug
        $course->slug = str()->slug($data['title'] . ' ' . $course->id);
        $course->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('Course created')));
        }
        return to_route('admin.courses.edit', ['course' => $course])->with('alert', generate_alert(__('Course created')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, $action = 'basic')
    {
        $this->getCourse($course->id);
        $categoriesHierarchy = CourseCategory::getHierarchy();
        $sections = [];
        if ($action == 'curriculum') {
            $user_id = lms_user_id();
            $sections = Section::where([
                ['user_id', $user_id],
                ['course_id', $course->id]
            ])->orderBy('sort_by', 'asc')->get();
        }
        return view('admin.courses.edit', compact('course', 'categoriesHierarchy', 'action', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->getCourse($course->id);
        $action = $request->action ?? 'basic';
        $rules = $messages = [];
        
        if($action === 'basic'){
            $rules = $this->getCourseBasicRules($course->id, $course->course_category_id);
            $messages = $this->getCourseBasicMessages();
        }else if($action === 'pricing'){
            $rules = $this->getCoursePricingRules();
            $messages = $this->getCoursePricingMessages();
        }else if($action === 'media'){
            $rules = $this->getCourseMediaRules();
            $messages = $this->getCourseMediaMessages();
        }

        // Validation begins
        $data = $request->validate(
            $rules,
            $messages
        );

        $course->updated_by_id = lms_user_id();
        // Validaion passes
        if($action === 'basic'){
            $course->title = $data['title'];
            $course->course_category_id = $data['course_category_id'];
            $course->level = $data['level'];
            $course->status = $data['status'];
            $course->short_description = $data['short_description'] ?? '';
            $course->description = $request->description;
            $course->save();
        }else if($action === 'pricing'){
            $course->is_paid = $data['is_paid'];
            if(isset($data['price'])) $course->price = $data['price'];
            if(isset($data['discount_flag'])) $course->discount_flag = $data['discount_flag'];
            if(isset($data['discounted_price'])) $course->discounted_price = $data['discounted_price'];
            $course->save();
        }else if($action === 'info'){
            $course->requirements = $request->requirements ? json_encode($request->requirements) : '';
            $course->outcomes = $request->outcomes ? json_encode($request->outcomes) : '';
            $course->faqs = $request->faqs ? json_encode($request->faqs) : '';
            $course->save();
        }else if($action === 'media'){
            $image = $request->file('thumbnail');
            if($image){
                $course->thumbnail = $image->store("courses/$course->id", 'public');
            }
            $image = $request->file('banner');
            if($image){
                $course->banner = $image->store("courses/$course->id", 'public');
            }
            $video = $request->file(key: 'uploaded_video_url');
            if($video){
                $course->uploaded_video_url = $video->store("courses/$course->id", 'public');
            }
            $course->is_online_video = $data['is_online_video'];
            if(isset($data['online_video_url'])) $course->online_video_url = $data['online_video_url'];
            $course->save();

            if($request->hasFile('gallery_images')){
                $sortOrder = $course->galleryImages()->max('sort_order') ?? 0;

                foreach($request->file('gallery_images') as $image){
                    $path = $image->store("courses/$course->id/gallery", 'public');

                    $course->galleryImages()->create([
                        'image' => $path,
                        'sort_order' => ++$sortOrder
                    ]);
                }
            }
        }else if($action === 'seo'){
            $validator = Validator::make($request->all(), [
                'meta_data.title' => 'nullable|min:10|max:80',
                'meta_data.description' => 'nullable|min:10|max:160',
                'meta_data.canonical_url' => 'nullable|url',
                'meta_data.og_image' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
                'slug' => ['required', 'string', function ($attribute, $value, $fail) use ($course) {
                    $exists = Course::where([
                        ['id', '<>', $course->id],
                        ['slug', '=', $value],
                    ])->exists();
        
                    if ($exists) {
                        $fail("The {$attribute} has already been taken.");
                    }
                }],
                // Add other validation rules as needed
            ], [
                'meta_data.title.required' => 'Meta Title is required',
                'meta_data.title.min' => 'Meta Title must be at least 10 characters',
                'meta_data.title.max' => 'Meta Title must be at most 80 characters',
                'meta_data.description.min' => 'Meta Title must be at least 20 characters',
                'meta_data.description.max' => 'Meta Title must be at most 160 characters',
                'meta_data.canonical_url.url' => 'Enter valid url',
                'meta_data.og_image' => [
                    'image' => 'The file must be a valid image.',
                    'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                    'max' => 'The image size should not exceed 1MB.',
                ],
            ]);
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $metaData = $request->input('meta_data', []);
            $image = $request->file('meta_data.og_image');
            if($image){
                $metaData['og_image'] = $image->store("courses/$course->id", 'public');
            }
            $course->meta_data = $metaData;

            $course->slug = $request['slug'];
            $course->save();
        }
        return to_route('admin.courses.custom-edit', ['course' => $course, 'action' => $action])->with('alert', generate_alert(__('Course updated')));
    }

    public function deleteGalleryImage(CourseGalleryImage $galleryImage)
    {
        $this->getCourse($galleryImage->course_id);

        if($galleryImage->image){
            Storage::disk('public')->delete($galleryImage->image);
        }

        $galleryImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }

    public function updateGalleryImageAltText(Request $request, CourseGalleryImage $galleryImage)
    {
        $this->getCourse($galleryImage->course_id);

        $data=$request->validate([
            'alt_text'=>'nullable|string|min:10|max:255'
        ]);

        $galleryImage->update([
            'alt_text'=>$data['alt_text'] ?? null
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Alt text updated successfully'
        ]);
    }

    /**
     * Activate / Deactivate an exam.
     */
    public function activate($courseId, $status)
    {
        $course = $this->getCourse($courseId);
        if($course){
            $course->status = $status == 1 ? 'Active' : 'Inactive';
            $course->save();
            return to_route('admin.courses.index')->with('alert', generate_alert(__(key: 'Course updated')));
        }else{
            return to_route('admin.courses.index')->with('alert', generate_alert(__(key: 'Course not available'), 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->getCourse($course->id);
        try {
            $course->delete();
            return redirect()->route('admin.courses.index')->with('alert', generate_alert(__('Course deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    /**
     * Create a new course section.
     */
    public function create_section(Request $request)
    {
        $id = (int) $request->id;
        $courseId = (int) $request->course_id;
        $this->getCourse($courseId);
        $validator = Validator::make($request->all(), [
            'title' => ['required','max:100',
            Rule::unique('sections')->where('course_id', $courseId)->ignore($id)],
            'course_id' => ['required','integer', 'exists:courses,id'],
        ], [
            'title.unique' => 'Duplicate section name',
            'course_id.exists' => 'Course not found for the given ID'
        ]);
    
        if ($validator->fails()) {
            $validator->errors()->add('section-error', 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user_id = $request->user()->id;
        $oldSection = Section::where([
            ['id', '=', $id],
            ['course_id', '=', $courseId],
            ['user_id', '=', $user_id]
        ])->first();
        $data = $validator->validated();
        if($oldSection){
            $oldSection->title = $data['title'];
            $oldSection->save();
        }else{
            $data['status'] = 1;
            $data['user_id'] = $user_id;
            $course = Section::create($data);
        }
        return to_route('admin.courses.custom-edit', ['course' => $data['course_id'], 'action' => 'curriculum'])->with('alert', generate_alert(__('Section created')));
    }

    /**
     * Remove course section.
     */
    public function delete_section(Request $request, $courseId, $id)
    {
        $this->getCourse($courseId);
        try {
            $section = Section::where([
                ['id', '=', $id],
                ['course_id', '=', $courseId],
                ['user_id', '=', lms_user_id()]
            ])->exists();
            if(!$section){
                abort(404, 'Section not found');
            }
            Section::where('id', $id)->delete();
            return to_route('admin.courses.custom-edit', ['course' => $courseId, 'action' => 'curriculum'])->with('alert', generate_alert(__('Section deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    /**
     * Sort course sections.
     */
    public function sort_sections(Request $request)
    {
        $ids = $request->ids;
		$ids = array_map('intval', $ids);
		foreach ($ids as $key => $value) {
            Section::where('id', $value)->update(['sort_by' => $key]);
		}
        return json_encode(['status' => 'success', 'message' => 'Sections sorted']);
    }

    public function layoutless(){
        return view('admin.layoutless', ['noLayout' => true]);
    }

    public function create_lesson(Request $request, $courseId){
        $course = $this->getCourse($courseId);
        $sectionId = (int) $request->section_id;
        if(!$course){
            abort(404);
        }
        $sections = Section::where('course_id', $courseId)->orderBy('sort_by', 'asc')->get();
        return view('admin.courses.edit.curriculum.create-lesson', ['noLayout' => true, 'course' => $course, 'courseId' => $courseId, 'sectionId' => $sectionId, 'sections' => $sections]);
    }

    public function edit_lesson(Request $request, $courseId, $id){
        $course = $this->getCourse($courseId);
        $lesson = Lesson::where([
            ['course_id', $courseId],
            ['is_quiz', 0],
            ['id', $id],
            ['user_id', lms_user_id()]
        ])->first();
        if(!$lesson){
            abort(404);
        }
        $sections = Section::where('course_id', $courseId)->orderBy('sort_by', 'asc')->get();
        return view('admin.courses.edit.curriculum.edit-lesson', ['noLayout' => true, 'lesson' => $lesson, 'course' => $course, 'courseId' => $courseId, 'sections' => $sections]);
    }

    /**
     * Save course lesson.
     */
    public function save_lesson(Request $request)
    {
        $id = $request->id;
        $data = $request->validate(
            $this->getLessonAddRules($id),
            $this->getLessonAddMessages()
        );
        $this->getCourse($data['course_id']);

        $lessonType = $data['lesson_type'];
        if($id){
            $lesson = Lesson::where([
                ['course_id', $data['course_id']],
                ['id', $id],
                ['is_quiz', 0],
                ['user_id', lms_user_id()]
            ])->first();
            if(!$lesson){
                abort(404);
            }
            $lesson->title = $data['title'];
            $lesson->lesson_type = $data['lesson_type'];
            $lesson->section_id = $data['section_id'];
            if(isset($data['lesson_src'])) $lesson->lesson_src = $data['lesson_src'];
            if(isset($data['duration'])) $lesson->duration = $data['duration'];
            if(isset($data['document_type'])) $lesson->document_type = $data['document_type'];
            if(isset($data['duration'])) $lesson->duration = $data['duration'];

            if($lessonType == 'youtube'){
                $lesson->thumbnail = lms_get_youtube_thumbnail($lesson->lesson_src);
            }else if($lessonType == 'vimeo'){
                $lesson->thumbnail = lms_get_vimeo_thumbnail($lesson->lesson_src);
            }else if($lessonType == 'dailymotion'){
                $lesson->thumbnail = lms_get_dailymotion_thumbnail($lesson->lesson_src);
            }

            $lesson->description = $request->description;
            $lesson->summary = $request->summary;
        }else{
            $data['status'] = 1;
            $data['is_quiz'] = 0;
            $data['user_id'] = $request->user()->id;
            $data['description'] = $request->description;
            $data['summary'] = $request->summary;
            $lesson = Lesson::create($data);
        }
        
        $uploadPath = "courses/lessons/$lesson->id";
        if(in_array($lessonType, ['upload', 'image', 'document'])){
            $file = $request->file('file');
            if($file){
                $lesson->lesson_src = $file->store($uploadPath, 'public');
            }
        }
        if(in_array($lessonType, ['mp4', 'upload'])){
            $file = $request->file('thumbnail');
            if($file){
                $lesson->thumbnail = $file->store($uploadPath, 'public');
            }
            $file = $request->file('caption');
            if($file){
                $originalExtension = $file->getClientOriginalExtension();
                $filename = uniqid() . '.' . $originalExtension;
                $lesson->caption = $file->storeAs($uploadPath, $filename, 'public');
            }            
        }
        $lesson->save();

        session()->flash('alert', generate_alert(__('Lesson deleted')));
        $redirectUrl = route('admin.courses.custom-edit', ['course' => $data['course_id'], 'action' => 'curriculum']);
        //return exit('<script>window.top.location.href = "http://127.0.0.1:8000/admin/courses/2/create-lesson"; </script>');        
        return exit('<script>window.top.location.href = "' . $redirectUrl . '"; </script>');        
    }

    public function delete_lesson(Request $request, $courseId, $sectionId, $id)
    {
        $this->getCourse($courseId);
        $lesson = Lesson::where([
            ['id', $id],
            ['course_id', $courseId],
            ['section_id', $sectionId],
            ['user_id', lms_user_id()]
        ])->first();
        if(!$lesson){
            abort(404, 'Lesson not found');
        }
        Lesson::where('id', $id)->delete();
        return to_route('admin.courses.custom-edit', ['course' => $courseId, 'action' => 'curriculum'])->with('alert', generate_alert(__(($lesson->is_quiz ? 'Quiz' : 'Lesson') . ' deleted')));
    }

    /**
     * Sort course lessons.
     */
    public function sort_lessons(Request $request)
    {
        $ids = $request->ids;
		$ids = array_map('intval', $ids);
		foreach ($ids as $key => $value) {
            Lesson::where('id', $value)->update(['sort_by' => $key]);
		}
        return json_encode(['status' => 'success', 'message' => 'Items sorted successfully']);
    }

    public function create_quiz(Request $request, $courseId){
        $course = $this->getCourse($courseId);
        $sectionId = (int) $request->section_id;
        if(!$course){
            abort(404);
        }
        $sections = Section::where('course_id', $courseId)->orderBy('sort_by', 'asc')->get();
        return view('admin.courses.edit.curriculum.create-quiz', ['noLayout' => true, 'course' => $course, 'courseId' => $courseId, 'sectionId' => $sectionId, 'sections' => $sections]);
    }

    public function edit_quiz(Request $request, $courseId, $id){
        $course = $this->getCourse($courseId);
        $lesson = Lesson::where([
            ['course_id', $courseId],
            ['is_quiz', 1],
            ['id', $id],
            ['user_id', lms_user_id()]
        ])->first();
        if(!$lesson){
            abort(404);
        }
        $sections = Section::where('course_id', $courseId)->orderBy('sort_by', 'asc')->get();
        return view('admin.courses.edit.curriculum.edit-quiz', ['noLayout' => true, 'lesson' => $lesson, 'course' => $course, 'courseId' => $courseId, 'sections' => $sections]);
    }

    /**
     * Save course quiz.
     */
    public function save_quiz(Request $request)
    {
        $data = $request->validate(
            $this->getQuizAddRules(),
            $this->getQuizAddMessages()
        );
        $this->getCourse($data['course_id']);
        $id = $request->id;

        if($id){
            $lesson = Lesson::where([
                ['course_id', $data['course_id']],
                ['id', $id],
                ['is_quiz', 1],
                ['user_id', lms_user_id()]
            ])->first();
            if(!$lesson){
                abort(404);
            }
            $lesson->title = $data['title'];
            $lesson->section_id = $data['section_id'];
            $lesson->duration = $data['duration'];
            $lesson->total_mark = $data['total_mark'];
            $lesson->pass_mark = $data['pass_mark'];
            $lesson->retake = $data['retake'];
            $lesson->description = $request->description;
            $lesson->save();
        }else{
            $data['status'] = 1;
            $data['is_quiz'] = 1;
            $data['user_id'] = $request->user()->id;
            $data['description'] = $request->description;
            $lesson = Lesson::create($data);
        }        

        session()->flash('alert', generate_alert(__('Lesson deleted')));
        $redirectUrl = route('admin.courses.custom-edit', ['course' => $data['course_id'], 'action' => 'curriculum']);
        return exit('<script>window.top.location.href = "' . $redirectUrl . '"; </script>');        
    }

    public function quiz_results($courseId, $lessonId){
        $course = $this->getCourse($courseId);
        $quizResultList = QuizResult::select('user_id', 
                                DB::raw('COUNT(*) as total_attempts'),
                                DB::raw('MAX(created_at) as last_attempt_at'),
                                DB::raw('(SELECT id FROM quiz_results AS qr WHERE qr.user_id = quiz_results.user_id ORDER BY created_at DESC LIMIT 1) as latest_id')
                            )
                            ->where('course_id', $courseId)
                            ->where('lesson_id', $lessonId)
                            ->groupBy('user_id')
                            ->orderByDesc('last_attempt_at')
                            ->paginate(lms_setting('admin_pagination_size'));
        $lesson = Lesson::find($lessonId);
        return view('admin.courses.edit.curriculum.quiz-results', ['course' => $course, 'lesson' => $lesson, 'quizResultList' => $quizResultList]);
    }

    public function quiz_result_details($courseId, $lessonId, $userId){
        $course = $this->getCourse($courseId);
        $quizResultList = QuizResult::where('course_id', $courseId)
                            ->where('lesson_id', $lessonId)
                            ->where('user_id', $userId)
                            ->paginate(lms_setting('admin_pagination_size'));
        $lesson = Lesson::find($lessonId);
        $user = User::find($userId);
        return view('admin.courses.edit.curriculum.quiz-result-details', ['course' => $course, 'lesson' => $lesson, 'user' => $user, 'quizResultList' => $quizResultList]);
    }

    public function questions(Request $request, $courseId, $lessonId){
        $this->getCourse($courseId);
        $lesson = Lesson::where([
            ['course_id', $courseId],
            ['id', $lessonId],
            ['is_quiz', 1],
            ['user_id', lms_user_id()]
        ])->first();
        if(!$lesson){
            abort(404);
        }

        $questions = Question::where([
            ['course_id', $courseId],
            ['lesson_id', $lessonId],
            ['user_id', lms_user_id()]
        ])->get();
        return view('admin.courses.edit.curriculum.questions', ['noLayout' => true, 'lesson' => $lesson, 'questions' => $questions]);
    } 

    public function delete_question(Request $request, $courseId, $sectionId, $lessonId, $id)
    {
        $this->getCourse($courseId);
        $question = Question::where([
            ['id', $id],
            ['course_id', $courseId],
            ['section_id', $sectionId],
            ['lesson_id', $lessonId],
            ['user_id', lms_user_id()]
        ])->first();
        if(!$question){
            abort(404, 'Question not found');
        }
        Question::where('id', $id)->delete();
        return to_route('admin.courses.questions', ['courseId' => $courseId, 'lessonId' => $lessonId])->with('alert', generate_alert(__('Question deleted')));
    }

    public function create_question(Request $request, $courseId, $lessonId){
        $this->getCourse($courseId);
        $lesson = Lesson::where([
            ['course_id', $courseId],
            ['id', $lessonId],
            ['user_id', lms_user_id()]
        ])->first();
        if(!$lesson){
            abort(404, 'Quiz not found');
        }
        $sectionId = $lesson->section_id;
        return view('admin.courses.edit.curriculum.create-question', ['noLayout' => true, 'courseId' => $courseId, 'sectionId' => $sectionId, 'lessonId' => $lessonId]);
    }

    /**
     * Save course question.
     */
    public function save_question(Request $request)
    {
        $id = $request->id;
        $data = $request->validate(
            $this->getQuestionAddRules($id),
            $this->getQuestionAddMessages()
        );
        $this->getCourse($data['course_id']);
        $questionType = $data['type'];
        if($questionType == 'multiple'){
            $delimiter = config('constants.ANSWER_DELIMITER');
            $data['options'] = $data['multiple_options'];
            $data['answer'] = implode($delimiter, $data['multiple_answers']);
            unset($data['multiple_options'], $data['multiple_answers']);
        }else if($questionType == 'fill'){
            $data['answer'] = $data['fill_answer'];
            unset($data['fill_answer']);
        }else if($questionType == 'yesno'){
            $data['answer'] = $data['yesno'];
            unset($data['yesno']);
        }

        if($id){
            $lesson = Lesson::where([
                ['course_id', $data['course_id']],
                ['id', $id],
                ['is_quiz', 1],
                ['user_id', lms_user_id()]
            ])->first();
            if(!$lesson){
                abort(404);
            }
            $data['title'] = preg_replace('/\s+/', ' ', $data['title']);
            $lesson->title = $data['title'];
            $lesson->section_id = $data['section_id'];
            $lesson->duration = $data['duration'];
            $lesson->total_mark = $data['total_mark'];
            $lesson->pass_mark = $data['pass_mark'];
            $lesson->retake = $data['retake'];
            $lesson->description = $request->description;
            $lesson->save();
        }else{
            $data['status'] = 1;
            $data['marks'] = 1;
            $data['user_id'] = $request->user()->id;
            $lesson = Question::create($data);
        }        

        return to_route('admin.courses.questions', ['courseId' => $data['course_id'], 'lessonId' => $data['lesson_id']])->with('alert', generate_alert(__('Question added')));
    }

    public function ratings($courseId){
        $course = $this->getCourse($courseId);
        $ratings = Rating::where([
            ['type', 'course'],
            ['type_id', $courseId],
            ['status', 1],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.courses.ratings', ['course' => $course, 'ratings' => $ratings]);
    }

    public function enrolments($courseId){
        $course = $this->getCourse($courseId);
        $enrolments = CourseEnroll::where([
            ['course_id', $courseId],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.courses.enrolments', ['course' => $course, 'enrolments' => $enrolments]);
    }
}
