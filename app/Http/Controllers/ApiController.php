<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnroll;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamCategory;
use App\Models\ExamEnroll;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\JobPost;
use App\Models\JobPostCategory;
use App\Models\JobPostEnroll;
use App\Models\Lesson;
use App\Models\OrganizationSetting;
use App\Models\OrganizationStudentUsage;
use App\Models\Payment;
use App\Models\Qbank;
use App\Models\QbankQuestion;
use App\Models\Question;
use App\Models\QuizAnswer;
use App\Models\QuizResult;
use App\Models\Rating;
use App\Models\RecordedVideo;
use App\Models\RecordedVideoCategory;
use App\Models\RecordedVideoEnroll;
use App\Models\Section;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wishlist;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\PersonalAccessToken;
use Mail;
use Storage;
use Str;

class ApiController extends Controller
{
    private $userId = 0;
    public function __construct(Request $request){
        if($request->user()){
            $this->userId = $request->user()->id;
        }
    }

    private function error_response($code, $message){
        $default = [
            'status' => 0
        ];
        return response()->json([
            ...$default,
            'message' => $message
        ], $code);
    }

    public function login(Request $request){
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:3',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();
        if ($user->role !== 'student') {
            Auth::logout();
    
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($user->status == 0) {
            Auth::logout();
            return response()->json([
                'status' => 0,
                'message' => 'Your account is inactive. Please contact admin.',
            ], 403);
        }

        //$user->tokens()->delete();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function register(Request $request){
        try {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', Rules\Password::defaults()],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }
        
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student'
        ]);

        //event(new Registered($user));

        Auth::login($user);

        $user = Auth::user();
        
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function social_login(Request $request){
        $data = false;
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'picture' => ['nullable', 'string', 'max:512'],
                'type' => ['required', 'string', 'in:google,facebook'],
                'id' => ['required', 'string'],
                'token' => ['required', 'string'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }
        
        $data = (object)$data;
        $email = $data->email;
        $token = $data->token;
        $login_from = $data->type . '_mobile';
        $user = User::where('email', $email)->first();
        if($user){
            if($user->role != 'student'){
                return $this->error_response(400, "There is already a {$user->role} account with this Email ID");
            }
            $user->first_name = explode(' ', $data->name)[0];
            $user->last_name = explode(' ', $data->name)[1] ?? '';
            $user->image = $data->picture ?? '';
            $user->{$data->type . '_id'} = $data->id;
            $user->google_token = $token ?? null;
            $user->login_from = $login_from;
            $user->save();
        }else{
            $password = lms_random_password();
            $user_data = [
                'first_name' => explode(' ', $data->name)[0],
                'last_name' => explode(' ', $data->name)[1] ?? '',
                'role' => 'student',
                'email' => $email,
                'image' => $data->picture ?? '',
                $data->type . '_id' => $data->id,
                'google_token' => $token,
                'login_from' => $login_from,
                'password' => $password,
            ];
            $user = User::create($user_data);
        }

        Auth::login($user);
        $user = Auth::user();
        //$user->tokens()->delete();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Authentication successful.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request){
        try {
            // Check if there's an Authorization header
            if (!$request->bearerToken()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Authorization Bearer token is missing.',
                ], 401);
            }

            // Attempt to delete the current token
            $user = $request->user();

            if (!$user || !$user->currentAccessToken()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid or expired token.',
                ], 401);
            }

            //$user->currentAccessToken()->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Logged out successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred while logging out.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function forgot_password(Request $request){
        try {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'exists:users,email'],
            ],[
                'email.email' => 'Invalid email address',
                'email.exists' => 'Student not found'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }

        $user = User::where([
            ['role', 'student'],
            ['email', $request->email]
        ])->first();
        if(!$user){
            return $this->error_response(404, 'Student not found');
        }
        if(!$user->status){
            return $this->error_response(404, 'Student account is disabled');
        }
        $randomNumber = mt_rand(100000, 999999);
        $user->fp_token = $randomNumber;
        $user->fp_date = now();
        $user->save();
        
        Mail::html("
                <h2>Hello, {$user->full_name}!</h2>
                <p>You are receiving this email because we received a password reset request for your account. Your OTP is</p>
                <h1>{$randomNumber}</h1>
            ", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Forgot Password');
            });

        return response()->json([
            'status' => 1,
            'message' => 'OTP was sent your registered email address.',
        ]);
    }

    public function reset_password(Request $request){
        try {
            $request->validate([
                'otp' => ['required', 'digits:6'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'exists:users,email'],
                'password' => ['required', Rules\Password::defaults()],
            ],[
				'otp.digits' => 'OTP must be of 6 digits',
                'email.email' => 'Invalid email address',
                'email.exists' => 'Student not found'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }

        $user = User::where([
            ['role', 'student'],
            ['email', $request->email]
        ])->first();
        if(!$user){
            return $this->error_response(404, 'Student not found');
        }
        if(!$user->status){
            return $this->error_response(404, 'Student account is disabled');
        }
        if(!$user->fp_token){
            return $this->error_response(404, 'Reset password was not requested');
        }
        if($user->fp_token != $request->otp){
            return $this->error_response(404, 'Invalid OTP');
        }
        $user->fp_token = '';
        $user->fp_date = now();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'Password reset successfully.',
        ]);
    }

    public function get_profile(Request $request){
        $user = $request->user();
        return response()->json([
            'status' => 1,
            'message' => '',
            'user' => $user
        ]);
    }

    public function update_profile(Request $request){
        try {
            //$jsonData = $request->json()->all();
            $data = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
            ]);
            $user = $request->user();
            /*$user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->save();*/
            $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
            ]);
            return response()->json([
                'status' => 1,
                'message' => 'Profile updated.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }
    }

    public function update_password(Request $request){
        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()], // New password rules
            ], [
                'current_password.current_password' => 'Current password is incorrect'
            ]);
        
            $user = $request->user();
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Password updated.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }
    }

    public function delete_account(Request $request){
        try {
            $user = User::where('status', 1)->find($request->user()->id);
            if(!$user){
                return $this->error_response(403, 'Account not found');
            }
            //$user->tokens()->delete();
            $user->email = "deleted_{$user->id}_{$user->email}";
            $user->status = -1;
            $user->save();

            return response()->json([
                'status' => 1,
                'message' => 'Account deleted.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => lms_show_validation_exception_message($e),
            ], 422);
        }
    }

    public function get_categories(){
        $categories = CourseCategory::getHierarchy(populateCourse: true);
        foreach ($categories as $key => &$d) {
            $d = lms_category_details($d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_categories_tree(){
        // Step 1: Fetch all categories
        $categories = CourseCategory::with(['courses' => function ($query) {
            $query->where('status', 'Active');
        }])->get();

        // Step 2: Index by ID
        $items = $categories->mapWithKeys(function ($item) {
            $item = lms_category_details($item);
            $array = $item->toArray();             // get all columns
            $array['sub_categories'] = [];         // add nested key
            return [$item->id => $array];  
        })->toArray();

        // Step 3: Build tree
        $tree = [];
        foreach ($items as $id => &$item) {
            if ($item['parent_id'] == 0) {
                $tree[] = &$item;
            } else {
                $items[$item['parent_id']]['sub_categories'][] = &$item;
            }
        }
        unset($item);

        // Step 4: Format output to remove parent_id
        $categories = $this->filterCategoriesWithCourses($tree);

        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    private function filterCategoriesWithCourses(array $categories): array
    {
        $filtered = [];

        foreach ($categories as $category) {
            // Recursively filter subcategories first
            $category['sub_categories'] = $this->filterCategoriesWithCourses($category['sub_categories']);

            $hasCourses = !empty($category['courses']);
            $hasSubCategories = !empty($category['sub_categories']);

            if ($hasCourses || $hasSubCategories) {
				foreach($category['courses'] as &$course){
					$course = lms_course_details($course, true);
				}
                $filtered[] = $category;
            }
        }

        return $filtered;
    }

    public function get_all_categories(){
        $categories = CourseCategory::getHierarchy();
        foreach ($categories as $key => &$d) {
            $d = lms_category_details($d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_parent_categories(){
        $categories = CourseCategory::where([
            ['parent_id', 0],
            ['status', 1]
        ])->get();
        foreach ($categories as $key => &$d) {
            $d = lms_category_details($d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_sub_categories($id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Category ID is required.',
            ], 404);
        }
        $categories = CourseCategory::where([
            ['parent_id', $id],
            ['status', 1]
        ])->get();
        foreach ($categories as $key => &$d) {
            $d = lms_category_details($d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_category_details($id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Category ID is required.',
            ], 404);
        }
        $data = CourseCategory::where('status', 1)->find($id);
        if($data){
            $data = lms_category_details($data);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Category not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'category' => $data
        ]);
    }

    public function get_courses(Request $request){
        $category_id = (int) $request->input('category_id');
        $condition = [
            ['status', 'Active']
        ];
        if($category_id){
            $data = CourseCategory::where([
                ['status', 1],
                ['id', $category_id]
            ])->exists();
            if(!$data){
                return response()->json([
                    'status' => 0,
                    'message' => 'Category not found.',
                ], 404);
            }
            $condition[] = ['course_category_id', $category_id];
        }
        $courses = Course::with([
            'category',
            //'sections.lessons.questions',
            'ratings'
        ])->where($condition)->get();

        foreach ($courses as $k => &$course) {
            $course->user_name = $course->user->full_name;
            if(isset($course->user)) unset($course->user);
            $course->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            /*$course->sections->each(function ($section) {
                $section->lessons->each(function ($lesson) {
                    $lesson->questions->each(function ($question) {
                        $delimiter = config('constants.ANSWER_DELIMITER');
                        if($question->type == 'multiple'){
                            $question->answer = explode($delimiter, $question->answer);
                        }else{
                            $question->answer = [$question->answer];
                        }
                        $question->options = $question->options ? explode($delimiter, $question->options) : [];
                    });
                });
            });*/
            $course->average_rating = $course->rating_average();
            $course->total_rating = $course->rating_count();
            $course->lesson_count = $course->lesson_count();
            $course->quiz_count = $course->quiz_count();
            $course->enroll_count = $course->enroll_count();
            $course = $course->toArray();
            lms_recursive_stripe_tags($course);
            $courses[$k] = lms_course_details((object)$course);
        }
        
        return response()->json([
            'status' => 1,
            'message' => '',
            'courses' => $courses
        ]);
    }

    public function autoEnrollCheck($type, $type_id, $type_category_id)
    {
        $organizationData = auth('sanctum')->user()->organization ?? false;
        if($organizationData){
            $organizationId = $organizationData->id;
            $no_of_students = $organizationData->no_of_students;
            $permissions = lms_organization_permissions($organizationData)[$type] ?? false;
            if($permissions){
                $usage = OrganizationStudentUsage::where([
                    ['organization_id', $organizationId],
                ])->count();
                $used = OrganizationStudentUsage::where([
                    ['organization_id', $organizationId],
                    ['user_id', $this->userId]
                ])->exists();
                if($no_of_students > $usage && !$used){
                    if(in_array($type_id, ($permissions['items'] ?? [])) || in_array($type_category_id, ($permissions['categories'] ?? []))){
                        OrganizationStudentUsage::create([
                            'organization_id' => $organizationId,
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

    public function get_course_details(Request $request, $id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Course ID is required.',
            ], 404);
        }
        if($request->bearerToken()){
            $this->userId = auth('sanctum')->id();
        }
        $data = Course::with([
            'category',
            'sections.lessons.questions',
            'ratings'
        ])->where('status', 'Active')->find($id);

        if($data){
            $data->user_name = $data->user->full_name;
            if(isset($data->user)) unset($data->user);
            $data->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $data->sections->each(function ($section) {
                $section->lessons->each(function ($lesson) {
                    if($lesson->lesson_src){
                        $lesson->lesson_src = lms_storage($lesson->lesson_src);
                    }
                    $lesson->questions->each(function ($question) {
                        $delimiter = config('constants.ANSWER_DELIMITER');
                        if($question->type == 'multiple'){
                            $question->answer = explode($delimiter, $question->answer);
                        }else{
                            $question->answer = [$question->answer];
                        }
                        $question->options = $question->options ? explode($delimiter, $question->options) : [];
                    });
                });
            });
            $data->is_enrolled = $data->enroll($this->userId);

            if(!$data->is_enrolled){
                $course = $data;
                if($this->autoEnrollCheck('courses', $course->id, $course->course_category_id)){
                    CourseEnroll::create([
                        'user_id' => $this->userId,
                        'course_id' => $course->id,
                        'is_enrolled' => true,
                        'enrolled_date' => now(),
                        'organization_id' => $course->organization_id,
                        'lesson_ids' => [],
                    ]);
                    $data->is_enrolled = true;
                    DB::table('cart') ->where('type', 'course') ->where('type_id', $id) ->delete();
                }
            }

            $data->wishlist_added = $data->wishlist($this->userId);
            $data->cart_added = $data->cart($this->userId);
            $data->average_rating = $data->rating_average();
            $data->total_rating = $data->rating_count();
            $data->enroll_count = $data->enroll_count();
            $data = $data->toArray();
            lms_recursive_stripe_tags($data, ['title', 'options', 'answer', 'description']);
            $data = lms_course_details((object)$data);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Course not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'course' => $data
        ]);
    }

    public function get_exam_categories(){
        $categories = ExamCategory::getHierarchy(populateExam: true);
        foreach ($categories as $key => &$d) {
            $d = lms_exam_category_details($d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_exam_category_details($id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Category ID is required.',
            ], 404);
        }
        $data = ExamCategory::where('status', 1)->find($id);
        if($data){
            $data = lms_exam_category_details($data);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Category not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'category' => $data
        ]);
    }

    public function get_exams(Request $request){
        $category_id = (int) $request->input('category_id');
        $condition = [
            ['status', 1]
        ];
        if($category_id){
            $data = ExamCategory::where([
                ['status', 1],
                ['id', $category_id]
            ])->exists();
            if(!$data){
                return response()->json([
                    'status' => 0,
                    'message' => 'Category not found.',
                ], 404);
            }
            $condition[] = ['exam_category_id', $category_id];
        }
        $exams = Exam::with([
            'category',
            //'questions',
            'ratings'
        ])->where($condition)->get()/*->toArray()*/;

        foreach ($exams as $key => &$d) {
            $d->user_name = $d->user->full_name;
            if(isset($d->user)) unset($d->user);
            $d->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $d->question_count = $d->question_count();
            $d->average_rating = $d->rating_average();
            $d->total_rating = $d->rating_count();
            $d->enroll_count = $d->enroll_count();
            lms_recursive_stripe_tags($d);
            $d = lms_exam_details((object)$d);
            /*if($d){
                $d = $this->adjustQuestionOptions($d);
            }*/
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'exams' => $exams
        ]);
    }

    private function adjustQuestionOptions($data){
        $questions = $data->questions;
        $delimiter = config('constants.ANSWER_DELIMITER');
        foreach ($questions as $k => &$q) {
            if($q['type'] == 'multiple'){
                $q['answer'] = explode($delimiter, $q['answer']);
            }else{
                $q['answer'] = [$q['answer']];
            }
            $q['options'] = $q['options'] ? explode($delimiter, $q['options']) : [];
        }
        $data->questions = $questions;
        return $data;
    }

    public function get_exam_details(Request $request, $id = null){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Exam ID is required');
        }
        if($request->bearerToken()){
            $this->userId = auth('sanctum')->id();
        }
        $data = Exam::with([
            'category',
            'questions',
            'ratings'
        ])->where('status', 1)->find($id);
        if($data){
            $data->user_name = $data->user->full_name;
            if(isset($data->user)) unset($data->user);
            $data->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $data->question_count = $data->question_count();
            $is_enrolled = $data->enroll($this->userId);

            if(!$is_enrolled){
                $exam = $data;
                if($this->autoEnrollCheck('exams', $exam->id, $exam->exam_category_id)){
                    ExamEnroll::create([
                        'user_id' => $this->userId,
                        'exam_id' => $exam->id,
                        'is_enrolled' => true,
                        'organization_id' => $exam->organization_id,
                    ]);
                    $is_enrolled = true;
                    DB::table('cart') ->where('type', 'exam') ->where('type_id', $id) ->delete();
                }
            }

            $data->wishlist_added = $data->wishlist($this->userId);
            $data->cart_added = $data->cart($this->userId);
            $data->average_rating = $data->rating_average();
            $data->total_rating = $data->rating_count();
            $data->enroll_count = $data->enroll_count();
            $data = $data->toArray(); // Don't move this line
            $data['is_enrolled'] = $is_enrolled;
            lms_recursive_stripe_tags($data, ['title', 'options', 'answer', 'description']);
            $data = lms_exam_details((object)$data);
            if($data){
                $data = $this->adjustQuestionOptions($data);
            }
            
        }else{
            return $this->error_response(404, 'Exam not found');
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'exam' => $data
        ]);
    }

    public function exam_enroll(Request $request, $id){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Exam ID is required');
        }
        $exam = Exam::where('status', 1)->find($id);
        if(!$exam){
            return $this->error_response(404, 'Exam not found');
        }
        if($exam->is_paid){
            $check_payment_id = Payment::where([
                ['payment_id', $request->razorpay_payment_id]
            ])->exists();
            if($check_payment_id){
                return $this->error_response(404, 'Duplicate Payment ID');
            }
            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_order_id = $request->razorpay_order_id;
            $razorpay_signature = $request->razorpay_signature;
            if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature) {
                return $this->error_response(404, 'This is paid exam. Please send payment data to enroll');
            }
            $secretKey = lms_setting('razorpay_api_secret');
            $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $secretKey);
            if ($generated_signature === $razorpay_signature) {
                $payment = [
                    'user_id' => $this->userId,
                    'type' => 'exam',
                    'type_id' => $exam->id,
                    'date' => now(),
                    'amount' => $exam->price,
                    'order_id' => $razorpay_order_id,
                    'payment_id' => $razorpay_payment_id,
                    'signature' => $razorpay_signature,
                    'status' => 'captured',
                    'source' => 'android',
                ];
                $new = Payment::create($payment);
            }else{
                return $this->error_response(404, 'Payment verification failed');
            }
        }
        $examEnrolled = ExamEnroll::where([
            ['user_id', $this->userId],
            ['exam_id', $id]
        ])->exists();
        if($examEnrolled){
            return $this->error_response(403, 'Already enrolled');
        }else{
            $exam = Exam::find($id);
            ExamEnroll::create([
                'user_id' => $this->userId,
                'exam_id' => $id,
                'is_enrolled' => true,
                'organization_id' => $exam->organization_id,
            ]);
            DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", ['exam', $id]);
            return response()->json([
                'status' => 1,
                'message' => 'Enrollment is successful'
            ]);
        }
    }

    private function adjust_question_options($current_questions){
        $current_questions->each(function ($question) {
            $delimiter = config('constants.ANSWER_DELIMITER');
            if($question->type == 'multiple'){
                $question->answer = explode($delimiter, $question->answer);
            }else{
                $question->answer = [$question->answer];
            }
            $question->options = $question->options ? explode($delimiter, $question->options) : [];
        });
        return $current_questions;
    }

    public function exam_questions(Request $request, $id){
        $id = (int)$id;
        $userId = $this->userId;
        if(!$id){
            return $this->error_response(400, 'Exam ID is required');
        }
        $exam = Exam::where('status', 1)->find($id);
        if(!$exam){
            return $this->error_response(404, 'Exam not found');
        }
        $examEnrolled = ExamEnroll::where([
            ['user_id', $userId],
            ['exam_id', $id]
        ])->exists();
        if(!$examEnrolled){
            return $this->error_response(403, 'You are not enrolled in this exam');
        }else{
            $all_questions = [];
            $attended_count = ExamResult::where([
                ['user_id', $this->userId],
                ['exam_id', $exam->id]
            ])->count();
            if($exam->retake != 0 && $attended_count >= $exam->retake){
                return $this->error_response(403, 'No more reattempt is allowed');
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
                $current_questions = $this->adjust_question_options($current_questions);
                $current_qbank_questions['questions'] = $current_questions;
                $qbank_questions []= $current_qbank_questions;
                $all_questions = array_merge($all_questions, $current_questions->toArray());
            }
            
            if(!$question_count){
                return $this->error_response(403, 'No questions available in this exam');
            }
            return response()->json([
                'status' => 1,
                'message' => 'Exam questions',
                'questions' => $all_questions
            ]);
        }
    }

    public function exam_submit_answers(Request $request, $id){

        $answers = $request->input('answers');
        if(!is_array($answers) || empty($answers)){
            return $this->error_response(400, 'Answers should be in array');
        }
        
        $userId = $this->userId;
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Exam ID is required');
        }
        $exam = Exam::where('status', 1)->find($id);
        if(!$exam){
            return $this->error_response(404, 'Exam not found');
        }
        $examEnrolled = ExamEnroll::where([
            ['user_id', $userId],
            ['exam_id', $id]
        ])->exists();
        if(!$examEnrolled){
            return $this->error_response(403, 'You are not enrolled in this exam');
        }else{
            $time_taken_seconds = (int) $request->input('time_taken');
            $time_taken = gmdate('H:i:s', $time_taken_seconds);
            $allowed_seconds = lms_duration_to_seconds($exam->duration);
            if($time_taken_seconds > $allowed_seconds){
                return $this->error_response(403, 'Time taken should not exceed ' . $allowed_seconds);
            }

            $attended_count = ExamResult::where([
                ['user_id', $userId],
                ['exam_id', $id]
            ])->count();
            if($exam->retake != 0 && $attended_count >= $exam->retake){
                return $this->error_response(403, 'No more retake is allowed');
            }
            $data_error = false;
            foreach ($answers as $k => $ans) {
                if(!is_array($ans['answer'])){
                    $data_error = true;
                    break;
                }
            }
            if($data_error){
                return $this->error_response(400, 'Incorrect answer format');
            }

            $qbank_ids = $exam->qbank_ids;
            $qbank_marks = array_column($qbank_ids, 'marks', 'id');
            $question_count = array_sum(array_column($qbank_ids, 'questions'));
            $answer_count = count($answers);
            if($question_count != $answer_count){
                return $this->error_response(400, 'Questions and answers count should be same');
            }

            $obtained_mark = $attempted = $correct_answers = $wrong_answers = 0;
            $bulk_answers = [];
            foreach ($answers as $qbank_question_id => $ans) {
                $qbank_question_id = $ans['id'];
                if(!isset($ans['answer'])) $ans['answer'] = [];
                $c_answer = array_map('trim', $ans['answer']); // Trim
                $c_answer = array_map(fn($value) => (string) $value, $c_answer); // Convert to string
                $c_answer = array_values(lms_array_filter($c_answer)); // Remove empty values and re-index
                if($c_answer){
                    $attempted++;
                }
                $qbankQuestion = QbankQuestion::select('id', 'qbank_id', 'type', 'title', 'options', 'answer', 'description', 'marks')->find($qbank_question_id);
                if(!$qbankQuestion) continue;

                $qbankQuestion->marks = (float) $qbank_marks[$qbankQuestion->qbank_id];
                $data = [];
                $data['user_id'] = $userId;
                $data['exam_id'] = $exam->id;
                $data['qbank_id'] = $qbankQuestion->qbank_id;
                $data['qbank_question_id'] = $qbank_question_id;
                $data['answer'] = is_array($c_answer) ? json_encode($c_answer) : [];
                $data['question'] = json_encode($qbankQuestion);
                $data['is_correct'] = false;

                $data['answer'] = implode(config('constants.ANSWER_DELIMITER'), $c_answer);
                $earned_marks = 0;
                if($qbankQuestion->answer == $data['answer']) {
                    $data['is_correct'] = true;
                    $obtained_mark += $qbankQuestion->marks;
                    $earned_marks = $qbankQuestion->marks;
                    $correct_answers++;
                }else if($data['answer']){
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
            
            $total_questions = ExamQuestion::where([
                ['exam_id', $exam->id],
                ['status', 1],
            ])->count();
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
            $result = $exam_result->toArray();
            $result['answers'] = [];

            foreach ($bulk_answers as &$a) {
                $a['exam_result_id'] = $exam_result->id; 
                $exam_answer = ExamAnswer::create($a)->toArray();
                $exam_answer['question'] = json_decode($exam_answer['question']);
                $exam_answer['is_correct'] = $exam_answer['is_correct'] ? 1 : 0;
                $result['is_pass'] = $result['is_pass'] ? 1 : 0;
                $result['answers'] []= $exam_answer;
            }

            $examAttended = ExamEnroll::where([
                ['user_id', $userId],
                ['exam_id', $exam->id],
                ['is_enrolled', true],
                ['is_attended', true]
            ])->exists();
            if(!$examAttended){
                $examEnroll = ExamEnroll::where([
                    ['user_id', $userId],
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
            
            return response()->json([
                'status' => 1,
                'message' => 'Submission is successful',
                'result' => $result
            ]);
        }
    }

    public function get_dashboard(Request $request){
        $userId = $this->userId;
        $exam_enrolled = ExamEnroll::where([
            ['user_id', $userId],
            ['is_enrolled', true]
        ])->count();
        $exam_attended = ExamEnroll::where([
            ['user_id', $userId],
            ['is_enrolled', true],
            ['is_attended', true]
        ])->count();
        $exams = ExamEnroll::where([
            ['user_id', $userId]
        ])->orderByDesc('created_at')->limit(5)->get();
        foreach($exams as &$e){
            $e->exam_title = $e->exam->title;
            if(isset($e->exam)) unset($e->exam);
            $e->status = $e->is_attended ? 'Completed' : 'Active';
        }

        $course_enrolled = CourseEnroll::where([
            ['user_id', $userId],
            ['is_enrolled', true]
        ])->count();
        $course_completed = CourseEnroll::where([
            ['user_id', $userId],
            ['is_enrolled', true],
            ['is_completed', true]
        ])->count();
        $courses = CourseEnroll::where([
            ['user_id', $userId]
        ])->orderByDesc('created_at')->limit(5)->get();
        foreach($courses as &$e){
            $e->course_title = $e->course->title;
            if(isset($e->course)) unset($e->course);
            $e->status = $e->is_completed ? 'Completed' : 'Active';
        }

        return response()->json([
            'status' => 1,
            'message' => 'Dashboard Details',
            'enrolled_exams' => $exam_enrolled,
            'active_exams' => $exam_attended,
            'completed_exams' => $exam_enrolled - $exam_attended,
            'last_5_exams' => $exams,
            'enrolled_courses' => $course_enrolled,
            'active_courses' => $course_enrolled - $course_completed,
            'completed_courses' => $course_completed,
            'last_5_courses' => $courses,
        ]);
    }

    public function get_my_exams(Request $request){
        $userId = $this->userId;
        $exams = ExamEnroll::with('exam.questions', 'attempts.answers')->where([
            ['user_id', $userId]
        ])->orderByDesc('created_at')->get();
        $fields = ['is_paid', 'price', 'duration', 'total_mark', 'pass_mark', 'retake', 'thumbnail'];
        foreach($exams as &$e){
            $e->exam = lms_exam_details((object)$e->exam);
            $e->question_count = $e->exam->question_count();
            $e->exam_title = $e->exam->title;
            $e->category_title = $e->exam->category->title;
            foreach($fields as $f){
                $e->{$f} = $e->exam->{$f};
            }
            $e->questions = $e->exam->questions;
            if(isset($e->exam)) unset($e->exam);
            $e->status = $e->is_attended ? 'Completed' : 'Active';
        }

        return response()->json([
            'status' => 1,
            'message' => '',
            'exams' => $exams,
        ]);
    }

    public function get_exam_results($id){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Exam ID is required');
        }
        $results = ExamResult::where([
            ['exam_id', $id],
            ['user_id', $this->userId]
        ])->get()->toArray();
        foreach ($results as &$att) {
            $att['answers'] = [];
            $att['percentage'] = (float)$att['percentage'];
            $exam_answers = ExamAnswer::where('exam_result_id', $att['id'])->get()->toArray();
            foreach ($exam_answers as &$ea) {
                $ea['question'] = json_decode($ea['question']);
                $att['answers'] []= $ea;
            }
        }

        return response()->json([
            'status' => 1,
            'message' => '',
            'results' => $results
        ]);
    }

    public function get_exam_result_details(int $examId, int $resultId){
        if(!$examId){
            return $this->error_response(400, 'Exam ID is required');
        }
        if(!$resultId){
            return $this->error_response(400, 'Result / Attempt ID is required');
        }
        $result = ExamResult::where([
            ['id', $resultId],
            ['exam_id', $examId],
            ['user_id', $this->userId]
        ])->first();
        if(!$result){
            return $this->error_response(404, 'Result / Attempt not found');
        }
        $result = $result->toArray();
        $result['percentage'] = (float)$result['percentage'];
        
        $result['answers'] = [];
        $exam_answers = ExamAnswer::where('exam_result_id', $result['id'])->get()->toArray();
        foreach ($exam_answers as &$ea) {
            $ea['question'] = json_decode($ea['question']);
            $result['answers'] []= $ea;
        }
        
        return response()->json([
            'status' => 1,
            'message' => '',
            'result' => $result
        ]);
    }

    public function course_enroll(Request $request, $id){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Course ID is required');
        }
        $course = Course::where('status', 'Active')->find($id);
        if(!$course){
            return $this->error_response(404, 'Course not found');
        }
        if($course->is_paid){
            $check_payment_id = Payment::where([
                ['payment_id', $request->razorpay_payment_id]
            ])->exists();
            if($check_payment_id){
                return $this->error_response(404, 'Duplicate Payment ID');
            }
            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_order_id = $request->razorpay_order_id;
            $razorpay_signature = $request->razorpay_signature;
            if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature) {
                return $this->error_response(404, 'This is paid course. Please send payment data to enroll');
            }
            $secretKey = lms_setting('razorpay_api_secret');
            $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $secretKey);
            if ($generated_signature === $razorpay_signature) {
                $payment = [
                    'user_id' => $this->userId,
                    'type' => 'course',
                    'type_id' => $course->id,
                    'date' => now(),
                    'amount' => $course->discount_flag ? $course->discounted_price : $course->price,
                    'order_id' => $razorpay_order_id,
                    'payment_id' => $razorpay_payment_id,
                    'signature' => $razorpay_signature,
                    'status' => 'captured',
                    'source' => 'android',
                ];
                $new = Payment::create($payment);
            }else{
                return $this->error_response(404, 'Payment verification failed');
            }
        }
        $courseEnrolled = CourseEnroll::where([
            ['user_id', $this->userId],
            ['course_id', $id]
        ])->exists();
        if($courseEnrolled){
            return $this->error_response(403, 'Already enrolled');
        }else{
            CourseEnroll::create([
                'user_id' => $this->userId,
                'course_id' => $id,
                'is_enrolled' => true,
                'organization_id' => $course->organization_id,
                'lesson_ids' => [],
            ]);
            DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", ['course', $id]);
            return response()->json([
                'status' => 1,
                'message' => 'Enrollment is successful'
            ]);
        }
    }

    public function wishlist(){
        $wishlist = Wishlist::where([
            ['status', 1],
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->get();
        foreach ($wishlist as &$d) {
            $item = $d->getItemAttribute();
			if(!$item) continue;
			$key = str_replace('-', '_', $d->type);
            $d->{$key} = match ($d->type) {
				'course' => lms_course_details($item),
				'exam'   => lms_exam_details($item),
				'recorded-video'   => lms_recorded_video_details($item),
				'job-post'   => lms_job_post_details($item),
				default  => null, // or throw exception / handle as needed
			};
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'wishlist' => $wishlist
        ]);
    }

    private function wishlist_save(Request $request, $action){
        $type = $request->type;
        $type_id = (int) $request->type_id;
        if(!$type || !$type_id) return $this->error_response(422, 'Validation error');
        $where = [
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        if($type == 'course'){
            $check_course = Course::where([
                ['status', 'Active'],
                ['id', $type_id]
            ])->exists();
            if(!$check_course) return $this->error_response(404, 'Course not found');
        }else if($type == 'exam'){
            $check_exam = Exam::where([
                ['status', 1],
                ['id', $type_id]
            ])->exists();
            if(!$check_exam) return $this->error_response(404, 'Exam not found');
        }
        $data = Wishlist::where($where)->select('id')->first();
        $status = 1;
        if($action == 'add'){
            if($data){
                $status = 0;
                $message = __('Already added');
            }else{
                $message = __('Added to wishlist');
                $obj = array_column($where, 1, 0);
                Wishlist::create($obj);
            }
        }else if($action == 'remove'){
            if($data){
                $data->delete();
                $message = __('Removed from wishlist');
            }else{
                $status = 0;
                $message = __('Item not available in wishlist');
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function wishlist_add(Request $request){
        return $this->wishlist_save($request, 'add');
    }

    public function wishlist_remove(Request $request){
        return $this->wishlist_save($request, 'remove');
    }

    public function cart(){
        $cart = Cart::where([
            ['is_paid', 0],
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->get();
        foreach ($cart as &$d) {
            $item = $d->getItemAttribute();
			if(!$item) continue;
			$key = str_replace('-', '_', $d->type);
            $d->{$key} = match ($d->type) {
				'course' => lms_course_details($item),
				'exam'   => lms_exam_details($item),
				'recorded-video'   => lms_recorded_video_details($item),
				'job-post'   => lms_job_post_details($item),
				default  => null, // or throw exception / handle as needed
			};
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'cart' => $cart
        ]);
    }

    private function cart_save(Request $request, $action){
        $type = $request->type;
        $type_id = (int) $request->type_id;
        if(!$type || !$type_id) return $this->error_response(422, 'Validation error');
        if(!in_array($type, ['course', 'exam', 'recorded-video', 'job-post'])) return $this->error_response(422, 'Invalid type');
        
        if($type == 'course'){
            $course = Course::find($type_id);
            if(!$course){
                return $this->error_response(404, 'Course not found');
            }
            $is_enrolled = CourseEnroll::where([
                ['user_id', $this->userId],
                ['course_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($is_enrolled){
                return $this->error_response(409, 'Course already enrolled');
            }
        }else if($type == 'exam'){
            $exam = Exam::find($type_id);
            if(!$exam){
                return $this->error_response(404, 'Exam not found');
            }
            $is_enrolled = ExamEnroll::where([
                ['user_id', $this->userId],
                ['exam_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($is_enrolled){
                return $this->error_response(409, 'Exam already enrolled');
            }
        }else if($type == 'recorded-video'){
            $recordedVideo = RecordedVideo::find($type_id);
            if(!$recordedVideo){
                return $this->error_response(404, 'Recorded Video not found');
            }
            $is_enrolled = RecordedVideoEnroll::where([
                ['user_id', $this->userId],
                ['recorded_video_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($is_enrolled){
                return $this->error_response(409, 'Recorded Video already enrolled');
            }
        }else if($type == 'job-post'){
            $jobPost = JobPost::find($type_id);
            if(!$jobPost){
                return $this->error_response(404, 'Job Post not found');
            }
            $is_enrolled = JobPostEnroll::where([
                ['user_id', $this->userId],
                ['job_post_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($is_enrolled){
                return $this->error_response(409, 'Job Post already enrolled');
            }
        }
        
        $where = [
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        $data = Cart::where($where)->select('id')->first();
        
        if((isset($course) && !$course->is_paid) || (isset($exam) && !$exam->is_paid) || 
            (isset($recordedVideo) && !$recordedVideo->is_paid) || (isset($jobPost) && !$jobPost->is_paid)){
            return $this->error_response(422, 'No payment is required for this');
        }

        $status = 1;
        if($action == 'add'){
            if($data){
                $status = 0;
                $message = __('Already added');
            }else{
                $message = __('Added to cart');
                $obj = array_column($where, 1, 0);
                if($type == 'course'){
                    $obj['amount'] = $course->discount_flag ? $course->discounted_price : $course->price;
                }else if($type == 'exam'){
                    $obj['amount'] = $exam->price;
                }else if($type == 'recorded-video'){
                    $obj['amount'] = $recordedVideo->price;
                }else if($type == 'job-post'){
                    $obj['amount'] = $jobPost->price;
                }
                Cart::create($obj);
            }
        }else if($action == 'remove'){
            if($data){
                $data->delete();
                $message = __('Removed from cart');
            }else{
                $status = 0;
                $message = __('Item not available in cart');
            }
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart_add(Request $request){
        return $this->cart_save($request, 'add');
    }

    public function cart_remove(Request $request){
        return $this->cart_save($request, 'remove');
    }

    public function cart_checkout(Request $request){  
        $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_order_id = $request->razorpay_order_id;
        $razorpay_signature = $request->razorpay_signature;
        if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature) {
            return $this->error_response(404, 'Please send payment data');
        }
        $check_payment_id = Payment::where([
            ['payment_id', $request->razorpay_payment_id]
        ])->exists();
        if($check_payment_id){
            return $this->error_response(404, 'Duplicate Payment ID');
        }
        $secretKey = lms_setting('razorpay_api_secret');
        $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $secretKey);
        if ($generated_signature !== $razorpay_signature) {
            return $this->error_response(404, 'Payment verification failed');
        }
        $cart_ids = $request->cart_ids;
        if(!is_array($cart_ids)) return $this->error_response(404, 'Cart IDs is invalid');
        $cart_ids = array_column($cart_ids, 'id');
        
        $cart_count = Cart::where([
            ['is_paid', 0],
            ['user_id', $this->userId]
        ])->whereIn('id', $cart_ids)->count();
        if($cart_count != count($cart_ids)){
            return $this->error_response(422, 'Mismatch in Cart Items Count / Invalid cart id given');
        }
        $amount = Cart::where([
            ['is_paid', 0],
            ['user_id', $this->userId]
        ])->whereIn('id', $cart_ids)->sum('amount');
        if(!$amount){
            return $this->error_response(422, 'Please add at least one course / exam to checkout. Amount should not be zero');
        }
        $razorpay_payment = lms_get_razorpay_payment($razorpay_payment_id);
        if($razorpay_payment && isset($razorpay_payment->status)){
            $payment_amount = $razorpay_payment->amount / 100;
            if($payment_amount != $amount){
                return $this->error_response(422, 'Mismatch in Cart Total Amount');
            }
            $payment = [
                'user_id' => $this->userId,
                'type' => 'cart',
                'type_id' => 0,
                'date' => now(),
                'amount' => $amount,
                'order_id' => $razorpay_order_id,
                'payment_id' => $razorpay_payment_id,
                'signature' => $razorpay_signature,
                'remarks' => json_encode($cart_ids),
                'status' => 'captured',
                'source' => 'android',
            ];
            $newPayment = Payment::create($payment);
            
            $cart = Cart::whereIn('id', $cart_ids)->get();
            if($cart){
                foreach ($cart as $k => $c) {
                    $c->is_paid = true;
                    $c->payment_id = $newPayment->id;
                    $c->status = 0;
                    $c->save();
                    if($c->type == 'course'){
                        $course = Course::find($c->type_id);
                        CourseEnroll::create([
                            'user_id' => $this->userId,
                            'course_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $course->organization_id,
                            'lesson_ids' => [],
                        ]);
                    }else if($c->type == 'exam'){
                        $exam = Exam::find($c->type_id);
                        ExamEnroll::create([
                            'user_id' => $this->userId,
                            'exam_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $exam->organization_id,
                        ]);
                    }else if($c->type == 'recorded-video'){
                        $recordedVideo = RecordedVideo::find($c->type_id);
                        RecordedVideoEnroll::create([
                            'user_id' => $this->userId,
                            'recorded_video_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $recordedVideo->organization_id,
                        ]);
                    }else if($c->type == 'job-post'){
                        $jobPost = JobPost::find($c->type_id);
                        JobPostEnroll::create([
                            'user_id' => $this->userId,
                            'job_post_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $jobPost->organization_id,
                        ]);
                    }
                    DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", [$c->type, $c->type_id]);
                }                
            }
    
            return response()->json([
                'status' => 1,
                'message' => 'Payment successful'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Error while fetching payment details'
            ]);
        }
    }

    public function get_my_courses(Request $request){
        $userId = $this->userId;
        $courses = CourseEnroll::with('course')->where([
            ['user_id', $userId]
        ])->orderByDesc('created_at')->get();
        foreach($courses as &$e){
            $e->course = lms_course_details((object)$e->course);
            $e->status = $e->is_completed ? 'Completed' : 'Active';
        }

        return response()->json([
            'status' => 1,
            'message' => '',
            'courses' => $courses,
        ]);
    }

    public function course_progress(Request $request, int $courseId, int $lessonId){
        $userId = $this->userId;
        $course = Course::where('status', 'Active')->find($courseId);
        if(!$course){
            return $this->error_response(404, 'Course not found');
        }
        $lesson = Lesson::where([
            ['status', 1],
            ['course_id', $courseId],
            ['id', $lessonId]
        ])->first();
        if(!$lesson){
            return $this->error_response(404, 'Lesson not found');
        }
        $enroll = CourseEnroll::where([
            ['user_id', $userId],
            ['course_id', $courseId]
        ])->first();
        if(!$enroll){
            return $this->error_response(403, 'Please enroll to this course first');
        }
        /*if($enroll->is_completed){
            return $this->error_response(403, 'Course already completed');
        }*/
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

        $lessonIds = [];
        $sections = $course->sections;
        $currentLessonFound = false;
        foreach ($sections as $section){
            foreach ($section->lessons as $currentLesson) {
                $lessonIds []= $currentLesson->id;
                if($lessonId == $currentLesson->id){
                    $currentLessonFound = true;
                    break;
                }
            }
            if($currentLessonFound){
                break;
            }
        }
        $enroll->lesson_ids = $lessonIds;
        $enroll->current_lesson_id = end($lessonIds);
        $enroll->save();

        $completed = false;
        if($allLessonIds == $lessonIds){
            $completed = true;
            $enroll->completed_date = now();
            $enroll->is_completed = true;
            $enroll->save();
        }

        return response()->json([
            'status' => 1,
            'message' => $completed ? 'Course completed' : 'Course progress updated',
        ]);
    }

    public function course_quiz_submit_answers(Request $request, int $courseId, int $lessonId){

        $answers = $request->input('answers');
        if(!is_array($answers) || empty($answers)){
            return $this->error_response(400, 'Answers should be in array');
        }
        
        $userId = $this->userId;
        if(!$courseId){
            return $this->error_response(400, 'Course ID is required');
        }
        if(!$lessonId){
            return $this->error_response(400, 'Exam ID is required');
        }
        $course = Course::where('status', 'Active')->find($courseId);
        if(!$course){
            return $this->error_response(404, 'Course not found');
        }
        $courseEnrolled = CourseEnroll::where([
            ['user_id', $userId],
            ['course_id', $courseId]
        ])->exists();
        if(!$courseEnrolled){
            return $this->error_response(403, 'Please enroll to this course first');
        }
        $quiz = Lesson::where([
            ['status', 1],
            ['is_quiz', 1]
        ])->find($lessonId);
        if(!$quiz){
            return $this->error_response(404, 'Quiz not found');
        }
        
        $time_taken_seconds = (int) $request->input('time_taken');
        $time_taken = gmdate('H:i:s', $time_taken_seconds);
        $allowed_seconds = lms_duration_to_seconds($quiz->duration);
        if($time_taken_seconds > $allowed_seconds){
            return $this->error_response(403, 'Time taken should not exceed ' . $allowed_seconds);
        }
        
        $attended_count = QuizResult::where([
            ['user_id', $userId],
            ['course_id', $courseId],
            ['lesson_id', $lessonId]
        ])->count();
        if($quiz->retake != 0 && $attended_count >= $quiz->retake){
            return $this->error_response(403, 'No more reattempt is allowed');
        }
        $questions = Question::where([
            ['status', 1],
            ['course_id', $courseId],
            ['lesson_id', $lessonId]
        ])->get();
        $question_count = count($questions);
        $answer_count = count($answers);
        if($question_count != $answer_count){
            return $this->error_response(400, 'Questions and answers count should be same');
        }
        $question_ids = $questions->pluck('id')->toArray();
        $question_id_error = false;
        $data_error = false;
        foreach ($answers as $k => $ans) {
            if(!in_array($ans['id'], $question_ids)){
                $question_id_error = true;
                break;
            }
            if(!is_array($ans['answer'])){
                $data_error = true;
                break;
            }
        }
        if($question_id_error){
            return $this->error_response(400, 'One or more question ID is wrong');
        }
        if($data_error){
            return $this->error_response(400, 'Incorrect answer format');
        }

        $obtained_mark = $attempted = 0;
        $bulk_answers = [];
        foreach ($answers as $quiz_question_id => $ans) {
            $quiz_question_id = $ans['id'];
            if(!isset($ans['answer'])) $ans['answer'] = [];
            $c_answer = array_map('trim', $ans['answer']); // Trim
            $c_answer = array_map(fn($value) => (string) $value, $c_answer); // Convert to string
            $c_answer = array_values(lms_array_filter($c_answer)); // Remove empty values and re-index
            if($c_answer){
                $attempted++;
            }
            $quizQuestion = Question::select('id', 'course_id', 'lesson_id', 'type', 'title', 'options', 'answer', 'description', 'marks')->find($quiz_question_id);
            if(!$quizQuestion) continue;
            $data = [];
            $data['user_id'] = $userId;
            $data['course_id'] = $courseId;
            $data['lesson_id'] = $lessonId;
            $data['question_id'] = $quiz_question_id;
            $data['answer'] = is_array($c_answer) ? json_encode($c_answer) : [];
            $data['question'] = json_encode($quizQuestion);
            $data['is_correct'] = false;

            $data['answer'] = implode(config('constants.ANSWER_DELIMITER'), $c_answer);
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
            $percentage = $obtained_mark * (100 / $quiz->total_mark);
            $is_pass = $obtained_mark >= $quiz->pass_mark;
        }
        $result = [];
        $result['user_id'] = $userId;
        $result['course_id'] = $courseId;
        $result['lesson_id'] = $lessonId;
        $result['total_questions'] = $total_questions;
        $result['total_mark'] = $quiz->total_mark;
        $result['pass_mark'] = $quiz->pass_mark;
        $result['obtained_mark'] = $obtained_mark;
        $result['percentage'] = $percentage;
        $result['attempted'] = $attempted;
        $result['time_taken'] = $time_taken;
        $result['is_pass'] = $is_pass;
        $quiz_result = QuizResult::create($result);
        $result = $quiz_result->toArray();
        $result['answers'] = [];

        foreach ($bulk_answers as $a) {
            $a['quiz_result_id'] = $quiz_result->id; 
            $quiz_answer = QuizAnswer::create($a)->toArray();
            $quiz_answer['question'] = json_decode($quiz_answer['question']);
            $quiz_answer['is_correct'] = $quiz_answer['is_correct'] ? 1 : 0;
            $result['is_pass'] = $result['is_pass'] ? 1 : 0;
            $result['answers'] []= $quiz_answer;
        }
        
        return response()->json([
            'status' => 1,
            'message' => 'Submission is successful',
            'result' => $result
        ]);
    }

    public function get_course_quiz_results(int $courseId, int $lessonId){
        if(!$lessonId){
            return $this->error_response(400, 'Quiz ID is required');
        }
        $results = QuizResult::where([
            ['user_id', $this->userId],
            ['course_id', $courseId],
            ['lesson_id', $lessonId]
        ])->get()->toArray();
        foreach ($results as &$att) {
            $att['answers'] = [];
            $att['percentage'] = (float)$att['percentage'];
            $quiz_answers = QuizAnswer::where('quiz_result_id', $att['id'])->get()->toArray();
            foreach ($quiz_answers as &$ea) {
                $ea['question'] = json_decode($ea['question']);
                $att['answers'] []= $ea;
            }
        }

        return response()->json([
            'status' => 1,
            'message' => '',
            'results' => $results
        ]);
    }
 
    public function get_course_quiz_results_details($courseId, $lessonId, $id){
        if(!$courseId){
            return $this->error_response(400, 'Course ID is required');
        }
        if(!$lessonId){
            return $this->error_response(400, 'Lesson ID is required');
        }
        if(!$id){
            return $this->error_response(400, 'Result ID is required');
        }
        $result = QuizResult::where([
            ['id', $id],
            ['course_id', $courseId],
            ['lesson_id', $lessonId],
            ['user_id', $this->userId]
        ])->first();
        if(!$result){
            return $this->error_response(404, 'Result not found');
        }
        $result = $result->toArray();
        $result['percentage'] = (float)$result['percentage'];
        
        $result['answers'] = [];
        $quiz_answers = QuizAnswer::where('quiz_result_id', $result['id'])->get()->toArray();
        foreach ($quiz_answers as &$ea) {
            $ea['question'] = json_decode($ea['question']);
            $result['answers'] []= $ea;
        }
        
        return response()->json([
            'status' => 1,
            'message' => '',
            'result' => $result
        ]);
    }

    public function get_ratings(){
        $ratings = Rating::where([
            ['status', 1],
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->get();
        foreach ($ratings as &$d) {
            $item = $d->getItemAttribute();
			if(!$item) continue;
			$key = str_replace('-', '_', $d->type);
            $d->{$key} = match ($d->type) {
				'course' => lms_course_details($item),
				'exam'   => lms_exam_details($item),
				'recorded-video'   => lms_recorded_video_details($item),
				'job-post'   => lms_job_post_details($item),
				default  => null, // or throw exception / handle as needed
			};
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'ratings' => $ratings
        ]);
    }

    public function ratings_add(Request $request){
        $type = $request->type;
        $type_id = (int) $request->type_id;
        if(!$type || !$type_id) return $this->error_response(422, 'Validation error');
        $where = [
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        if($type == 'course'){
            $check_course = Course::where([
                ['status', 'Active'],
                ['id', $type_id]
            ])->exists();
            if(!$check_course) return $this->error_response(404, 'Course not found');
        }else if($type == 'exam'){
            $check_exam = Exam::where([
                ['status', 1],
                ['id', $type_id]
            ])->exists();
            if(!$check_exam) return $this->error_response(404, 'Exam not found');
        }
        $data = Rating::where($where)->select('id')->exists();
        $status = 1;
        if($data){
            $status = 0;
            $message = __('Already added');
        }else{
            
            try {
                $data = $request->validate([
                    'rating'  => 'required|integer|between:1,5',
                    'message' => 'required|string|max:1200',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'status' => 0,
                    'message' => lms_show_validation_exception_message($e),
                ], 422);
            }

            $message = __('Rating added');
            $obj = array_column($where, 1, 0);
            $obj['rating'] = (int) $request->rating;
            $obj['message'] = $request->message;
            Rating::create($obj);
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function get_settings(Request $request){
        $can_login = true;
        if ($request->bearerToken()) {
            $accessToken = PersonalAccessToken::findToken($request->bearerToken());
            if ($accessToken) {
                $user = $accessToken->tokenable;
                $can_login = $user->status == 1;
            }
        }
        $settings = Setting::select(['key', 'value', 'description'])->get();
        $sliders = [
            [
                'url' => 'https://app.neomatrixsolutions.com/assets/images/mobile-banners/banner-1.png'
            ],
            [
                'url' => 'https://app.neomatrixsolutions.com/assets/images/mobile-banners/banner-2.png'
            ],
            [
                'url' => 'https://app.neomatrixsolutions.com/assets/images/mobile-banners/banner-3.png'
            ],
            [
                'url' => 'https://app.neomatrixsolutions.com/assets/images/mobile-banners/banner-4.png'
            ],
            [
                'url' => 'https://app.neomatrixsolutions.com/assets/images/mobile-banners/banner-5.png'
            ],
        ];
        return response()->json([
            'status' => 1,
            'message' => '',
            'can_login' => $can_login,
            'sliders' => $sliders,
            'settings' => $settings,
        ]);
    }
    
    public function get_course_certificate(Request $request, $id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Course ID is required.',
            ], 404);
        }
        $course = Course::where('status', 'Active')->find($id);

        $url = '';
        if($course){
            $courseCompleted = CourseEnroll::where([
                ['user_id', $this->userId],
                ['course_id', $id],
                ['is_completed', true]
            ])->first();
            if(!$courseCompleted){
                return response()->json([
                    'status' => 0,
                    'message' => 'You did not complete the course',
                ], 404);
            }

            $user = Auth::user();
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
            $pdf = Pdf::loadView("frontend/certificate/course-$template", $data);
        
            $uniqueId = lms_uuid();
            $certificatePath = "certificates/courses/{$uniqueId}-{$course->id}-{$user->id}.pdf";
            Storage::disk('public')->put($certificatePath, $pdf->output());

            $url = lms_storage($certificatePath);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Course not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'url' => $url
        ]);
    }

    public function get_exam_certificate(Request $request, $id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Exam ID is required.',
            ], 404);
        }
        $exam = Exam::where('status', 1)->find($id);

        $url = '';
        if($exam){
            $examCompleted = ExamEnroll::where([
                ['user_id', $this->userId],
                ['exam_id', $id],
                ['is_attended', true]
            ])->first();
            if(!$examCompleted){
                return response()->json([
                    'status' => 0,
                    'message' => 'You did not attend the exam',
                ], 404);
            }

            $user = Auth::user();
            $bestPercentage = ExamResult::where([
                ['user_id', $this->userId],
                ['exam_id', $exam->id]
            ])->max('percentage');
            $grade = lms_calculate_grade($bestPercentage);
            $organization = $user->organization;
            $template = lms_setting('exam_certificate_template');
            $data = [
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

            $url = lms_storage($certificatePath);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Exam not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'url' => $url
        ]);
    }

    public function get_recorded_video_categories(){
        $categories = RecordedVideoCategory::with('recorded_videos')
            ->where('status', 1)
            ->get()
            ->map(function ($category) {
                $category = lms_recorded_video_category_details($category);
                $category->recorded_videos = $category->recorded_videos->map(function ($video) {
                    return lms_recorded_video_details($video);
                });

                return $category;
            });
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_recorded_video_category_details($id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Category ID is required.',
            ], 404);
        }
        $data = RecordedVideoCategory::where('status', 1)->find($id);
        if($data){
            $data = lms_recorded_video_category_details($data);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Category not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'category' => $data
        ]);
    }

    public function get_recorded_videos(Request $request){
        $category_id = (int) $request->input('category_id');
        $condition = [
            ['status', 1]
        ];
        if($category_id){
            $data = RecordedVideoCategory::where([
                ['status', 1],
                ['id', $category_id]
            ])->exists();
            if(!$data){
                return response()->json([
                    'status' => 0,
                    'message' => 'Category not found.',
                ], 404);
            }
            $condition[] = ['recorded_video_category_id', $category_id];
        }
        $recorded_videos = RecordedVideo::with([
            'category',
            'ratings'
        ])->where($condition)->get();

        foreach ($recorded_videos as $key => &$d) {
            $d->user_name = $d->user->full_name;
            if(isset($d->user)) unset($d->user);
            $d->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $d->average_rating = $d->rating_average();
            $d->total_rating = $d->rating_count();
            $d->enroll_count = $d->enroll_count();
            lms_recursive_stripe_tags($d);
            $d = lms_recorded_video_details((object)$d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'recorded_videos' => $recorded_videos
        ]);
    }

    public function get_recorded_video_details(Request $request, $id = null){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Recorded Video ID is required');
        }
        if($request->bearerToken()){
            $this->userId = auth('sanctum')->id();
        }
        $data = RecordedVideo::with([
            'category',
            'ratings'
        ])->where('status', 1)->find($id);
        if($data){
            $data->user_name = $data->user->full_name;
            if(isset($data->user)) unset($data->user);
            $data->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $is_enrolled = $data->enroll($this->userId);

            if(!$is_enrolled){
                $recordedVideo = $data;
                if($this->autoEnrollCheck('recorded_videos', $recordedVideo->id, $recordedVideo->recorded_video_category_id)){
                    RecordedVideoEnroll::create([
                        'user_id' => $this->userId,
                        'recorded_video_id' => $recordedVideo->id,
                        'is_enrolled' => true,
                        'enrolled_date' => now(),
                        'organization_id' => $recordedVideo->organization_id,
                    ]);
                    $is_enrolled = true;
                    DB::table('cart') ->where('type', 'recorded-video') ->where('type_id', $id) ->delete();
                }
            }

            $data->cart_added = $data->cart($this->userId);
            $data->average_rating = $data->rating_average();
            $data->total_rating = $data->rating_count();
            $data->enroll_count = $data->enroll_count();
            $data = $data->toArray(); // Don't move this line
            $data['is_enrolled'] = $is_enrolled;
            lms_recursive_stripe_tags($data, ['title', 'options', 'answer', 'description']);
            $data = lms_recorded_video_details((object)$data);
            
        }else{
            return $this->error_response(404, 'Recorded Video not found');
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'recorded_video' => $data
        ]);
    }

    public function get_job_post_categories(){
        $categories = JobPostCategory::with('job_posts')
            ->where('status', 1)
            ->get()
            ->map(function ($category) {
                $category = lms_job_post_category_details($category);
                $category->job_posts = $category->job_posts->map(function ($video) {
                    return lms_job_post_details($video);
                });

                return $category;
            });
        return response()->json([
            'status' => 1,
            'message' => '',
            'categories' => $categories
        ]);
    }

    public function get_job_post_category_details($id = null){
        $id = (int)$id;
        if(!$id){
            return response()->json([
                'status' => 0,
                'message' => 'Category ID is required.',
            ], 404);
        }
        $data = JobPostCategory::where('status', 1)->find($id);
        if($data){
            $data = lms_job_post_category_details($data);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Category not found.',
            ], 404);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'category' => $data
        ]);
    }

    public function get_job_posts(Request $request){
        $category_id = (int) $request->input('category_id');
        $condition = [
            ['status', 1]
        ];
        if($category_id){
            $data = JobPostCategory::where([
                ['status', 1],
                ['id', $category_id]
            ])->exists();
            if(!$data){
                return response()->json([
                    'status' => 0,
                    'message' => 'Category not found.',
                ], 404);
            }
            $condition[] = ['job_post_category_id', $category_id];
        }
        $job_posts = JobPost::with([
            'category',
            'ratings'
        ])->where($condition)->get();

        foreach ($job_posts as $key => &$d) {
            $d->user_name = $d->user->full_name;
            if(isset($d->user)) unset($d->user);
            $d->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $d->average_rating = $d->rating_average();
            $d->total_rating = $d->rating_count();
            $d->enroll_count = $d->enroll_count();
            lms_recursive_stripe_tags($d);
            $d = lms_job_post_details((object)$d);
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'job_posts' => $job_posts
        ]);
    }

    public function get_job_post_details(Request $request, $id = null){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Job Post ID is required');
        }
        if($request->bearerToken()){
            $this->userId = auth('sanctum')->id();
        }
        $data = JobPost::with([
            'category',
            'ratings'
        ])->where('status', 1)->find($id);
        if($data){
            $data->user_name = $data->user->full_name;
            if(isset($data->user)) unset($data->user);
            $data->ratings->each(function ($rating) {
                $rating->user_name = $rating->user->full_name;
                if(isset($rating->user)) unset($rating->user);
            });
            $is_enrolled = $data->enroll($this->userId);

            if(!$is_enrolled){
                $jobPost = $data;
                if($this->autoEnrollCheck('job_posts', $jobPost->id, $jobPost->job_post_category_id)){
                    JobPostEnroll::create([
                        'user_id' => $this->userId,
                        'job_post_id' => $jobPost->id,
                        'is_enrolled' => true,
                        'enrolled_date' => now(),
                        'organization_id' => $jobPost->organization_id,
                    ]);
                    $is_enrolled = true;
                    DB::table('cart') ->where('type', 'job-post') ->where('type_id', $id) ->delete();
                }
            }
        
            $data->cart_added = $data->cart($this->userId);
            $data->average_rating = $data->rating_average();
            $data->total_rating = $data->rating_count();
            $data->enroll_count = $data->enroll_count();
            $data = $data->toArray(); // Don't move this line
            $data['is_enrolled'] = $is_enrolled;
            lms_recursive_stripe_tags($data, ['title', 'options', 'answer', 'description']);
            $data = lms_job_post_details((object)$data);            
        }else{
            return $this->error_response(404, 'Job Post not found');
        }
        return response()->json([
            'status' => 1,
            'message' => '',
            'job_post' => $data
        ]);
    }

    public function get_my_recorded_videos(Request $request){
        $userId = $this->userId;
        $recorded_videos = RecordedVideoEnroll::with('recorded_video')->where([
            ['user_id', $userId]
        ])->orderByDesc('created_at')->get();
        $fields = ['is_paid', 'price', 'thumbnail', 'link'];
        foreach($recorded_videos as &$e){
            $e->recorded_video = lms_recorded_video_details((object)$e->recorded_video);
            $e->recorded_video_title = $e->recorded_video->title;
            $e->category_title = $e->recorded_video->category->title;
            foreach($fields as $f){
                $e->{$f} = $e->recorded_video->{$f};
            }
            if(isset($e->recorded_video)) unset($e->recorded_video);
        }

        return response()->json([
            'status' => 1,
            'message' => '',
            'recorded_videos' => $recorded_videos,
        ]);
    }

    public function get_my_job_posts(Request $request){
        $userId = $this->userId;
        $job_posts = JobPostEnroll::with('job_post')->where([
            ['user_id', $userId]
        ])->orderByDesc('created_at')->get();
        $fields = ['is_paid', 'price', 'thumbnail', 'link'];
        foreach($job_posts as &$e){
            $e->job_post = lms_job_post_details((object)$e->job_post);
            $e->job_post_title = $e->job_post->title;
            $e->category_title = $e->job_post->category->title;
            foreach($fields as $f){
                $e->{$f} = $e->job_post->{$f};
            }
            if(isset($e->job_post)) unset($e->job_post);
        }

        return response()->json([
            'status' => 1,
            'message' => '',
            'job_posts' => $job_posts,
        ]);
    }

    public function job_post_enroll(Request $request, $id){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Job Post ID is required');
        }
        $jobPost = JobPost::where('status', 1)->find($id);
        if(!$jobPost){
            return $this->error_response(404, 'Job Post not found');
        }
        if($jobPost->is_paid){
            $check_payment_id = Payment::where([
                ['payment_id', $request->razorpay_payment_id]
            ])->exists();
            if($check_payment_id){
                return $this->error_response(404, 'Duplicate Payment ID');
            }
            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_order_id = $request->razorpay_order_id;
            $razorpay_signature = $request->razorpay_signature;
            if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature) {
                return $this->error_response(404, 'This is paid job post. Please send payment data to enroll');
            }
            $secretKey = lms_setting('razorpay_api_secret');
            $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $secretKey);
            if ($generated_signature === $razorpay_signature) {
                $payment = [
                    'user_id' => $this->userId,
                    'type' => 'job-post',
                    'type_id' => $jobPost->id,
                    'date' => now(),
                    'amount' => $jobPost->price,
                    'order_id' => $razorpay_order_id,
                    'payment_id' => $razorpay_payment_id,
                    'signature' => $razorpay_signature,
                    'status' => 'captured',
                    'source' => 'android',
                ];
                $new = Payment::create($payment);
            }else{
                return $this->error_response(404, 'Payment verification failed');
            }
        }
        $jobPostEnrolled = JobPostEnroll::where([
            ['user_id', $this->userId],
            ['job_post_id', $id]
        ])->exists();
        if($jobPostEnrolled){
            return $this->error_response(403, 'Already enrolled');
        }else{
            $jobPost = JobPost::find($id);
            JobPostEnroll::create([
                'user_id' => $this->userId,
                'job_post_id' => $id,
                'is_enrolled' => true,
                'organization_id' => $jobPost->organization_id,
            ]);
            return response()->json([
                'status' => 1,
                'message' => 'Enrollment is successful'
            ]);
        }
    }

    public function recorded_video_enroll(Request $request, $id){
        $id = (int)$id;
        if(!$id){
            return $this->error_response(400, 'Recorded Video ID is required');
        }
        $recordedVideo = RecordedVideo::where('status', 1)->find($id);
        if(!$recordedVideo){
            return $this->error_response(404, 'Recorded Video not found');
        }
        if($recordedVideo->is_paid){
            $check_payment_id = Payment::where([
                ['payment_id', $request->razorpay_payment_id]
            ])->exists();
            if($check_payment_id){
                return $this->error_response(404, 'Duplicate Payment ID');
            }
            $razorpay_payment_id = $request->razorpay_payment_id;
            $razorpay_order_id = $request->razorpay_order_id;
            $razorpay_signature = $request->razorpay_signature;
            if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature) {
                return $this->error_response(404, 'This is paid recorded video. Please send payment data to enroll');
            }
            $secretKey = lms_setting('razorpay_api_secret');
            $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $secretKey);
            if ($generated_signature === $razorpay_signature) {
                $payment = [
                    'user_id' => $this->userId,
                    'type' => 'recorded-video',
                    'type_id' => $recordedVideo->id,
                    'date' => now(),
                    'amount' => $recordedVideo->price,
                    'order_id' => $razorpay_order_id,
                    'payment_id' => $razorpay_payment_id,
                    'signature' => $razorpay_signature,
                    'status' => 'captured',
                    'source' => 'android',
                ];
                $new = Payment::create($payment);
            }else{
                return $this->error_response(404, 'Payment verification failed');
            }
        }
        $recordedVideoEnrolled = RecordedVideoEnroll::where([
            ['user_id', $this->userId],
            ['recorded_video_id', $id]
        ])->exists();
        if($recordedVideoEnrolled){
            return $this->error_response(403, 'Already enrolled');
        }else{
            $recordedVideo = RecordedVideo::find($id);
            RecordedVideoEnroll::create([
                'user_id' => $this->userId,
                'recorded_video_id' => $id,
                'is_enrolled' => true,
                'organization_id' => $recordedVideo->organization_id,
            ]);
            return response()->json([
                'status' => 1,
                'message' => 'Enrollment is successful'
            ]);
        }
    }
}
