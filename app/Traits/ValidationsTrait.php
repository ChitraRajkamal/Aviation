<?php

namespace App\Traits;

use Illuminate\Validation\Rule;

trait ValidationsTrait
{
    public function getCourseCategoryRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        return [
            'title' => [
                'required', 
                'min:2', 
                /*Rule::unique('course_categories')->where(function ($query) {
                    return $query->where('parent_id', $this->parent_id);
                }),*/
                Rule::unique('course_categories')->where('parent_id', $categoryId)->ignore($id)
            ],
            ///'parent_id' => ['integer', 'exists:course_categories,id'],
            //'parent_id' => ['integer'],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function getCourseCategoryMessages()
    {
        return [
            'title.required' => 'Category title is required',
            'title.min' => 'Category title should at least be of 2 characters',
            'parent_id.exists' => 'Selected parent category is not available',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
        ];
    }

    public function getCourseAddRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        $rules = [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('courses')->where('course_category_id', $categoryId)->ignore($id)
            ],
            'course_category_id' => ['required', 'integer', 'exists:course_categories,id'],
            'level' => ['required', 'in:' . implode(',', lms_course_levels())],            
            'is_paid' => ['required', 'in:0,1'],
            'status' => ['required', 'in:' . implode(',', lms_course_status())],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'short_description' => ['nullable'],
        ];

        if (request()->input('is_paid') == 1 || request()->isMethod('get')) {
            $rules['price'] = ['required', 'decimal:0,2', 'gt:0']; // At least one digit before decimal and 2
            $rules['discount_flag'] = ['required', 'integer'];            
        }

        if (request()->input('discount_flag') == 1 || request()->isMethod('get')) {
            $rules['discounted_price'] = ['required', 'decimal:0,2', 'lt:price', 'not_in:0.00']; 
        }
        return $rules;
    }

    public function getCourseAddMessages()
    {
        return [
            'title.required' => 'Course title is required',
            'title.min' => 'Course title should at least be of 2 characters',
            'course_category_id.exists' => 'Selected category is not available',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
            'price.decimal' => 'Price should be of 0 to 2 decimal points',
            'price.gt' => 'Price must be greater than 0',
            'discounted_price.lt' => 'Discounted Price must be less than the Price',
            'discounted_price.not_in' => 'Discounted Price cannot be 0',
        ];
    }

    public function getCourseBasicRules($id = 0, $categoryId = 0)
    {
        $rules = [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('courses')->where('course_category_id', $categoryId)->ignore($id)
            ],
            'course_category_id' => ['required', 'integer', 'exists:course_categories,id'],
            'level' => ['required', 'in:' . implode(',', lms_course_levels())],
            'status' => ['required', 'in:' . implode(',', lms_course_status())],
            'short_description' => ['nullable'],
        ];
        return $rules;
    }

    public function getCourseBasicMessages()
    {
        return [
            'title.required' => 'Course title is required',
            'title.min' => 'Course title should at least be of 2 characters',
            'course_category_id.exists' => 'Selected category is not available',
        ];
    }

    public function getCoursePricingRules()
    {
        $rules = [
            'is_paid' => ['required', 'in:0,1'],
        ];

        if (request()->input('is_paid') == 1 || request()->isMethod('get')) {
            $rules['price'] = ['required', 'decimal:0,2', 'gt:0']; // At least one digit before decimal and 2
            $rules['discount_flag'] = ['required', 'integer'];            
        }

        if (request()->input('discount_flag') == 1 || request()->isMethod('get')) {
            $rules['discounted_price'] = ['required', 'decimal:0,2', 'lt:price', 'not_in:0.00'];
        }
        return $rules;
    }

    public function getCoursePricingMessages()
    {
        return [
            'price.decimal' => 'Price should be of 0 to 2 decimal points',
            'price.gt' => 'Price must be greater than 0',
            'discounted_price.lt' => 'Discounted Price must be less than the Price',
            'discounted_price.not_in' => 'Discounted Price cannot be 0',
        ];
    }

    public function getCourseMediaRules()
    {
        $rules = [
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'banner' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'is_online_video' => ['in:0,1'],
            'uploaded_video_url' => 'file|mimes:mp4,webm,ogg|max:2048',
            'gallery_images' => 'nullable|array|max:20',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if (request()->input('is_online_video') == 1) {
            $rules['online_video_url'] = [
                'url',
                'regex:/^(https?:\/\/(?:www\.)?(youtube\.com\/watch\?v=|vimeo\.com\/|dailymotion\.com\/|.+\.(mp4|webm|ogg)))/'
            ];            
        }
        return $rules;
    }

    public function getCourseMediaMessages()
    {
        return [
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
            'banner' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
            'uploaded_video_url' => [
                'image' => 'The file must be a valid video.',
                'mimes' => 'Only mp4, webm, and ogg formats are allowed.',
                'max' => 'The image size should not exceed 2MB.',
            ],
            'online_video_url.regex' => 'Url can be YouTube / Vimeo / Dailymotion / Any valid online video url (mp4 / webm / ogg)',
        ];
    }

    public function getLessonAddRules($id = 0)
    {
        $id = (int)$id;
        $lessonTypes = lms_lesson_type();
        $rules = [
            'title' => [
                'required', 
                'min:2'
            ],
            'lesson_type' => ['required', 'in:' . implode(',', array_keys($lessonTypes))],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id']
        ];

        $lessonType = request()->input('lesson_type');
        if (in_array($lessonType, ['youtube', 'vimeo', 'dailymotion', 'google', 'mp4', 'iframe'])) {
            $_validation = ['required'];
            if($lessonType == 'youtube'){
                $_validation []= 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]{11}$/';
            }else if($lessonType == 'vimeo'){
                $_validation []= 'regex:/^https?:\/\/(?:www\.)?vimeo\.com\/\d+$/';
            }else if($lessonType == 'dailymotion'){
                $_validation []= 'regex:/^https?:\/\/(?:www\.)?dailymotion\.com\/video\/[a-zA-Z0-9]+(?:[?#].*)?$/';
            }else if($lessonType == 'google'){
                $_validation []= 'regex:/^https?:\/\/(?:www\.)?drive\.google\.com\/(?:file\/d\/|uc\?id=)([a-zA-Z0-9_-]+)/';
            }else if($lessonType == 'mp4'){
                $_validation []= 'regex:/^https?:\/\/(?:www\.)?[\w.-]+\.[a-z]{2,6}(?:\/[\w.-]+)*\/[\w-]+\.(mp4)$/i';
            }else if($lessonType == 'iframe'){
                $_validation []= 'url';
            }
            $rules['lesson_src'] = $_validation;           
        }
        
        if (in_array($lessonType, ['youtube', 'vimeo', 'dailymotion', 'mp4', 'upload', 'google'])) {
            $rules['duration'] = ['required', 'regex:/^(?:\d{1,2}):(?:[0-5]\d):(?:[0-5]\d)$/', 'not_in:00:00:00'];
        }

        if(!$id){ // Validation only for create lesson
            if($lessonType == 'upload'){
                $rules['file'] = ['required', 'file', 'mimes:mp4', 'max:1024'];
            }else if($lessonType == 'image'){
                $rules['file'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:1024'];
            }else if($lessonType == 'document'){
                $rules['document_type'] = ['required', 'in:' . implode(',', array_keys(lms_document_type()))];
                $rules['file'] = ['required', 'file', 'max:5120']; // 5 * 1024 = 5120
            }
            
            if(in_array($lessonType, ['mp4', 'upload'])){
                $rules['thumbnail'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:1024'];
                //$rules['caption'] = ['required', 'file', 'mimetypes:text/vtt,text/plain', 'max:1024'];
            }
        }        
        
        return $rules;
    }

    public function getLessonAddMessages()
    {
        $messages = [
            'title.required' => 'Lesson title is required',
            'title.min' => 'Lesson title should at least be of 2 characters',
            'course_id.exists' => 'Course is not available',
            'section_id.exists' => 'Section is not available',
            'lesson_src.required' => 'Lesson title is required',
            'duration.not_in' => 'Duration is required and cannot be "00:00:00"',
        ];

        $lessonType = request()->input('lesson_type');
        if (in_array($lessonType, ['youtube', 'vimeo', 'dailymotion', 'google', 'mp4'])) {
            $messages['lesson_src.required'] = ucfirst($lessonType) . ' url is required';
            $messages['lesson_src.regex'] = ucfirst($lessonType) . ' url is not valid';
        }

        if (in_array($lessonType, ['youtube', 'vimeo', 'dailymotion', 'mp4', 'upload', 'google'])) {
            $messages['duration.required'] = 'Duration is required';
            $messages['duration.regex'] = 'Duration is not valid. Ex: 2 Hours 15 minutes 30 Seconds = 02:15:30';
        }

        if($lessonType == 'upload'){
            $messages['file.required'] = 'MP4 Video is required';
            $messages['file.mimes'] = 'Only MP4 Video is allowed';
            $messages['file.max'] = 'The MP4 video size should not exceed 2MB';
        }else if($lessonType == 'image'){
            $messages['file.required'] = 'Image is required';
            $messages['file.image'] = 'The file must be a valid image';
            $messages['file.mimes'] = 'Only jpeg, png, jpg, and gif formats are allowed';
            $messages['file.max'] = 'The image size should not exceed 1MB';
        }else if($lessonType == 'document'){
            $messages['file.required'] = 'Document is required';
            $messages['file.max'] = 'The document size should not exceed 5MB';
        }else if($lessonType == 'iframe'){
            $messages['lesson_src.required'] = ucfirst($lessonType) . ' url is required';
            $messages['lesson_src.url'] = ucfirst($lessonType) . ' url is not valid';
        }
        
        if(in_array($lessonType, ['mp4', 'upload'])){
            $messages['thumbnail.required'] = 'Thumbnail is required';
            $messages['thumbnail.image'] = 'The file must be a valid image';
            $messages['thumbnail.mimes'] = 'Only jpeg, png, jpg, and gif formats are allowed';
            $messages['thumbnail.max'] = 'The image size should not exceed 1MB';
            $messages['caption.required'] = 'Caption is required';
            $messages['caption.mimetypes'] = 'Only vtt format are allowed';
            $messages['caption.max'] = 'The file size should not exceed 1MB';
        }
        return $messages;
    }

    public function getQuizAddRules()
    {
        $rules = [
            'title' => [
                'required', 
                'min:2'
            ],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'duration' => ['required', 'regex:/^(?:\d{1,2}):(?:[0-5]\d):(?:[0-5]\d)$/', 'not_in:00:00:00'],
            'total_mark' => ['required', 'integer', 'min:1'],
            'pass_mark' => [
                'required',
                'integer',
                'min:1',
                'lte:total_mark',
            ],
            'retake' => ['required', 'integer', 'gte:0'],
        ];
        
        return $rules;
    }

    public function getQuizAddMessages()
    {
        $messages = [
            'title.required' => 'Lesson title is required',
            'title.min' => 'Lesson title should at least be of 2 characters',
            'course_id.exists' => 'Course is not available',
            'section_id.exists' => 'Section is not available',
            'duration.not_in' => 'Duration is required and cannot be "00:00:00"',
            'duration.regex' => 'Duration is not valid. Ex: 2 Hours 15 minutes 30 Seconds = 02:15:30',
            'total_mark.required' => 'Total marks are required.',
            'total_mark.integer' => 'Total marks must be an integer.',
            'total_mark.min' => 'Total marks must be at least 1.',
            'pass_mark.required' => 'Pass marks are required.',
            'pass_mark.integer' => 'Pass marks must be an integer.',
            'pass_mark.min' => 'Pass marks must be at least 1.',
            'pass_mark.lte' => 'Pass marks cannot exceed total marks.',
            'retake.required' => 'Reattempts are required.',
            'retake.min' => 'Reattempts must be at least 1.',
        ];
        return $messages;
    }

    public function getQuestionAddRules($id = 0)
    {
        $rules = [
            'title' => [ 'required', 'min:2' ],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'type' => ['required', 'in:' . implode(',', array_keys(lms_exam_question_type()))]
        ];

        $questionType = request()->input('type');
        
        if($questionType == 'multiple'){
            $rules['multiple_options'] = ['required'];
            $rules['multiple_answers'] = ['required'];
        }else if($questionType == 'fill'){
            $rules['fill_answer'] = ['required'];
        }else if($questionType == 'yesno'){
            $rules['yesno'] = ['required'];
        }
        
        return $rules;
    }

    public function getQuestionAddMessages()
    {
        $messages = [
            'title.required' => 'Question title is required',
            'title.min' => 'Question title should at least be of 2 characters',
            'course_id.exists' => 'Course is not available',
            'section_id.exists' => 'Section is not available',
            'lesson_id.exists' => 'Question is not available',
        ];
        $questionType = request()->input('type');
        if($questionType == 'multiple'){
            $messages['multiple_options.required'] = 'Please provide at least one options';
            $messages['multiple_answers.required'] = 'Please provide at least one answer';
        }else if($questionType == 'fill' || $questionType == 'yesno'){
            $messages['fill_answer.required'] = 'Answer is required';
        }
        return $messages;
    }

    public function getExamCategoryRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        return [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('exam_categories')->where('parent_id', $categoryId)->ignore($id)
            ],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function getExamCategoryMessages()
    {
        return [
            'title.required' => 'Category title is required',
            'title.min' => 'Category title should at least be of 2 characters',
            'parent_id.exists' => 'Selected parent category is not available',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
        ];
    }

    public function getExamAddRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        $rules = [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('exams')->where('exam_category_id', $categoryId)->ignore($id)
            ],
            'price' => ['decimal:0,2', 'gt:-1'],
            'exam_category_id' => ['required', 'integer', 'exists:exam_categories,id'],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'duration' => ['required', 'regex:/^(?:\d{1,2}):(?:[0-5]\d):(?:[0-5]\d)$/', 'not_in:00:00:00'],
            'total_mark' => ['required', 'integer', 'min:1'],
            'pass_mark' => [
                'required',
                'integer',
                'min:1',
                'lte:total_mark',
            ],
            'retake' => ['required', 'integer', 'gte:0'],
            'qbank_ids' => ['required'],
            'negative_mark' => ['required', 'integer', 'gte:0'],
            'meta_data.title' => 'nullable|min:10|max:80',
            'meta_data.description' => 'nullable|min:10|max:160',
            'meta_data.canonical_url' => 'nullable|url',
            'meta_data.og_image' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
        ];
        
        return $rules;
    }

    public function getExamAddMessages()
    {
        return [
            'title.required' => 'Exam title is required',
            'title.min' => 'Exam title should at least be of 2 characters',
            'exam_category_id.exists' => 'Selected category is not available',
            'price.decimal' => 'Price should be of 0 to 2 decimal points',
            'price.gt' => 'Price must be greater than or equal to 0',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
            'duration.not_in' => 'Duration is required and cannot be "00:00:00"',
            'duration.regex' => 'Duration is not valid. Ex: 2 Hours 15 minutes 30 Seconds = 02:15:30',
            'total_mark.required' => 'Total marks are required.',
            'total_mark.integer' => 'Total marks must be an integer.',
            'total_mark.min' => 'Total marks must be at least 1.',
            'pass_mark.required' => 'Pass marks are required.',
            'pass_mark.integer' => 'Pass marks must be an integer.',
            'pass_mark.min' => 'Pass marks must be at least 1.',
            'pass_mark.lte' => 'Pass marks cannot exceed total marks.',
            'retake.required' => 'Reattempts are required.',
            'negative_mark.required' => 'Negative marks are required.',
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
        ];
    }

    public function getExamQuestionAddRules()
    {
        $rules = [
            'title' => [ 'required', 'min:2' ],
            'marks' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $questionId = request()->input('id');
                    $examId = request()->route('examId');
                    $exam = \App\Models\Exam::find($examId);
                    $marks = \App\Models\ExamQuestion::where([
                        ['status', 1],
                        ['exam_id', $examId],
                        ['id', '!=', $questionId]
                    ])->sum('marks');
                    
                    if ($exam && ($marks + $value) > $exam->total_mark) {
                        $fail("The {$attribute} must not exceed the total marks of the exam ({$exam->total_mark}).");
                    }
                }
            ],
            'type' => ['required', 'in:' . implode(',', array_keys(lms_exam_question_type()))]
        ];

        $questionType = request()->input('type');
        
        if($questionType == 'multiple'){
            $rules['multiple_options'] = ['required'];
            $rules['multiple_answers'] = ['required'];
        }else if($questionType == 'fill'){
            $rules['fill_answer'] = ['required'];
        }else if($questionType == 'yesno'){
            $rules['yesno'] = ['required'];
        }
        
        return $rules;
    }

    public function getExamQuestionAddMessages()
    {
        $messages = [
            'title.required' => 'Question title is required',
            'title.min' => 'Question title should at least be of 2 characters',
            'marks.min' => 'Marks should be greater than 0',
        ];
        $questionType = request()->input('type');
        if($questionType == 'multiple'){
            $messages['multiple_options.required'] = 'Please provide at least one options';
            $messages['multiple_answers.required'] = 'Please provide at least one answer';
        }else if($questionType == 'fill' || $questionType == 'yesno'){
            $messages['fill_answer.required'] = 'Answer is required';
        }
        return $messages;
    }

    public function getStudentAddRules($id = 0)
    {
        $rules = [
            'first_name' => ['required', 'min:2' ],
            'last_name' => ['required' ],
            'email' => ['required', 'email', "unique:users,email,$id"],
            'mobile' => ['required'],
            'phone' => ['nullable'],
            'gender' => ['required', 'in:' . implode(',', lms_gender_types())],
            'dob' => ['required', 'date', 'before:' . today()->subYears(lms_setting('user_min_age'))->format('Y-m-d')],
        ];
        
        return $rules;
    }

    public function getStudentAddMessages()
    {
        return [
            'first_name.required' => 'First Name is required',
            'first_name.min' => 'First Name should at least be of 2 characters',
            'last_name.required' => 'Last Name is required',
            'email.required' => 'Email ID is required',
            'email.email' => 'Invalid Email ID',
            'email.unique' => 'The email has already been taken',
            'dob.required' => 'DOB is required',
            'dob.date' => 'Invalid DOB Date',
            'dob.before' => 'Minimum age is ' . lms_setting('user_min_age'),
            'mobile.required' => 'Mobile is required',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Invalid Gender',
        ];
    }

    public function getOrganizationAddRules($id = 0, $organizationAdmin = true)
    {
        $rules = [
            'first_name' => ['required', 'min:2' ],
            'last_name' => ['required' ],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'mobile' => ['nullable'],
            'phone' => ['nullable'],
            'gender' => ['nullable', 'in:' . implode(',', lms_gender_types())],
            'dob' => ['nullable', 'date', 'before:' . today()->subYears(lms_setting('user_min_age'))->format('Y-m-d')],
        ];
        if($organizationAdmin){
            $rules['organization_name'] = ['required', 'min:2' ];
            $rules['organization_email'] = ['nullable', 'email'];
        }else{
            $rules['role_id'] = ['required', 'exists:organization_roles,id' ];
        }
        return $rules;
    }

    public function getOrganizationAddMessages()
    {
        return [
            'organization_name.required' => 'Organization Name is required',
            'organization_name.min' => 'Organization Name should at least be of 2 characters',
            'organization_email.email' => 'Invalid Email ID',
            'first_name.required' => 'First Name is required',
            'first_name.min' => 'First Name should at least be of 2 characters',
            'last_name.required' => 'Last Name is required',
            'email.required' => 'Email ID is required',
            'email.email' => 'Invalid Email ID',
            'email.unique' => 'The email has already been taken',
            'dob.date' => 'Invalid DOB Date',
            'dob.before' => 'Minimum age is ' . lms_setting('user_min_age'),
            'gender.in' => 'Invalid Gender',
            'role_id.required' => 'Role is required',
            'role_id.exists' => 'Role does not exist',
        ];
    }

    public function getOrganizationRoleAddRules($id = 0)
    {
        $rules = [
            'name' => [
                'required', 
                'min:2', 
                Rule::unique('organization_roles')->where('organization_id', lms_organization_id())->ignore($id)
            ],
            'description' => ['nullable'],
            'menu_ids' => ['required'],
        ];
        return $rules;
    }

    public function getOrganizationRoleAddMessages()
    {
        return [
            'name.required' => 'Role Name is required',
            'name.min' => 'Role Name should at least be of 2 characters',
            'name.unique' => 'Role Name must be unique.',
            'menu_ids.required' => 'Please select at least one menu',
        ];
    }

    public function getQbankAddRules($id = 0)
    {
        $rules = [
            'title' => ['required','min:2'],
            'description' => ['nullable'],
        ];
        
        return $rules;
    }

    public function getQbankAddMessages()
    {
        return [
            'title.required' => 'Question bank title is required',
            'title.min' => 'Question bank title should at least be of 2 characters',
        ];
    }

    public function getQbankQuestionAddRules()
    {
        $rules = [
            'title' => [ 'required', 'min:2' ],
            /*'marks' => [
                'required',
                'integer',
                'min:1',
            ],*/
            'type' => ['required', 'in:' . implode(',', array_keys(lms_exam_question_type()))]
        ];

        $questionType = request()->input('type');
        
        if($questionType == 'multiple'){
            $rules['multiple_options'] = ['required'];
            $rules['multiple_answers'] = ['required'];
        }else if($questionType == 'fill'){
            $rules['fill_answer'] = ['required'];
        }else if($questionType == 'yesno'){
            $rules['yesno'] = ['required'];
        }
        
        return $rules;
    }

    public function getQbankQuestionAddMessages()
    {
        $messages = [
            'title.required' => 'Question title is required',
            'title.min' => 'Question title should at least be of 2 characters',
            //'marks.min' => 'Marks should be greater than 0',
        ];
        $questionType = request()->input('type');
        if($questionType == 'multiple'){
            $messages['multiple_options.required'] = 'Please provide at least one options';
            $messages['multiple_answers.required'] = 'Please provide at least one answer';
        }else if($questionType == 'fill' || $questionType == 'yesno'){
            $messages['fill_answer.required'] = 'Answer is required';
        }
        return $messages;
    }

    public function getJobPostCategoryAddRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        return [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('job_post_categories')->where('parent_id', $categoryId)->ignore($id)
            ],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function getJobPostCategoryAddMessages()
    {
        return [
            'title.required' => 'Category title is required',
            'title.min' => 'Category title should at least be of 2 characters',
            'parent_id.exists' => 'Selected parent category is not available',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
        ];
    }

    public function getJobPostAddRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        return [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('job_posts')->where('job_post_category_id', $categoryId)->ignore($id)
            ],
            'price' => ['decimal:0,2', 'gt:-1'],
            'job_post_category_id' => ['required', 'integer', 'exists:job_post_categories,id'],
            'link' => ['required', 'url'],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function getJobPostAddMessages()
    {
        return [
            'title.required' => 'Job post title is required',
            'title.min' => 'Job post title should at least be of 2 characters',
            'price.decimal' => 'Price should be of 0 to 2 decimal points',
            'price.gt' => 'Price must be greater than or equal to 0',
            'job_post_category_id.exists' => 'Selected category is not available',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
        ];
    }

    public function getRecordedVideoCategoryAddRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        return [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('recorded_video_categories')->where('parent_id', $categoryId)->ignore($id)
            ],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function getRecordedVideoCategoryAddMessages()
    {
        return [
            'title.required' => 'Category title is required',
            'title.min' => 'Category title should at least be of 2 characters',
            'parent_id.exists' => 'Selected parent category is not available',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
        ];
    }

    public function getRecordedVideoAddRules($id = 0, $categoryId = 0)
    {
        $categoryId = $categoryId ?? 0;
        return [
            'title' => [
                'required', 
                'min:2', 
                Rule::unique('recorded_videos')->where('recorded_video_category_id', $categoryId)->ignore($id)
            ],
            'price' => ['decimal:0,2', 'gt:-1'],
            'recorded_video_category_id' => ['required', 'integer', 'exists:recorded_video_categories,id'],
            'link' => ['nullable', 'url', 'regex:/^(https?:\/\/(?:www\.)?(youtube\.com\/watch\?v))/'],
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function getRecordedVideoAddMessages()
    {
        return [
            'title.required' => 'Recorded video title is required',
            'title.min' => 'Recorded video title should at least be of 2 characters',
            'price.decimal' => 'Price should be of 0 to 2 decimal points',
            'price.gt' => 'Price must be greater than or equal to 0',
            'recorded_video_category_id.exists' => 'Selected category is not available',
            'link.regex' => 'Please enter valid YouTube video link',
            'thumbnail' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => 'The image size should not exceed 1MB.',
            ],
        ];
    }

    public function getSubscriptionAddRules()
    {
        return [
            'organization_id' => ['required', 'exists:organizations,id'],
            'no_of_students' => ['required', 'gt:0'],
            'allow_courses' => ['required', 'boolean'],
            'allow_exams' => ['required', 'boolean'],
            'allow_job_posts' => ['required', 'boolean'],
            'allow_recorded_videos' => ['required', 'boolean'],
            'price' => ['decimal:0,2', 'gt:0'],
        ];
    }

    public function getSubscriptionAddMessages()
    {
        return [
            'organization_id.required' => 'Organization is required',
            'organization_id.exists' => 'Selected organization is not available',
            'no_of_students.required' => 'No of students is required',
            'allow_courses.required' => 'Allow Courses is required',
            'allow_exams.required' => 'Allow Exams is required',
            'allow_job_posts.required' => 'Allow Job Posts is required',
            'allow_recorded_videos.required' => 'Allow Recorded Videos is required',
            'price.decimal' => 'Price should be of 0 to 2 decimal points',
            'price.gt' => 'Price must be greater than or equal to 1',
        ];
    }
}
