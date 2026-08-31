<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CourseCategory;
use App\Models\CourseEnroll;
use App\Models\ExamCategory;
use App\Models\ExamEnroll;
use App\Traits\ValidationsTrait;
use Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Str;

class AppServiceProvider extends ServiceProvider
{
    use ValidationsTrait;
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*if ($this->app->environment('local')) {
            Mail::alwaysTo('hussainmh39@gmail.com');
        }*/

        view()->composer('*', function ($view){
            $user = request()->user();
            $user_image = $user->image ?? false;
            if(!$user_image) $user_image = asset('assets/images/profile-60.png');
            else $user_image = lms_storage($user_image);
            $view->with('global_user_image', $user_image);
            $view->with('global_is_organization', ($user->organization->id ?? 0) > 0);
            $view->with('global_organization_id', $user->organization->id ?? 0);
            $view->with('global_organization_permissions', $user->organization->organization_permissions ?? false);
        });

        view()->composer('layouts.frontend.header', function ($view){
            $course_categories = CourseCategory::getCategoryWithCourseCount();
            $exam_categories = ExamCategory::getCategoryWithExamCount();
            $cart_count = Cart::where([
                ['status', 1],
                ['user_id', auth()->id()]
            ])->count();
            $view->with('global_course_categories', $course_categories);
            $view->with('global_exam_categories', $exam_categories);
            $view->with('global_cart_count', $cart_count);
        });

        view()->composer('layouts.frontend.dashboard-header', function ($view) {
            $global_course_enrolled = CourseEnroll::where([
                ['user_id', auth()->id()],
                ['is_enrolled', true]
            ])->count();
            $view->with('global_course_enrolled', $global_course_enrolled);
            $global_exam_enrolled = ExamEnroll::where([
                ['user_id', auth()->id()],
                ['is_enrolled', true]
            ])->count();
            $view->with('global_exam_enrolled', $global_exam_enrolled);
        });

        /* Validation Part */
        Blade::directive('lmsparsley', function ($field) {
            list($module, $field) = explode(',', trim($field, '\'"')); // Remove quotes from field name
            $module = trim($module);
            $field = trim($field);
            /*$request = new ProductRequest();
            //request()->input($key)
            $rules = $request->rules();
            $messages = $request->messages();*/

            /*$viewData = app('view')->databasePath();
            p($viewData);
            
            $rules = $viewData['rules'][$field] ?? [];
            $messages = $viewData['messages'];
            p($rules, false);
            p($messages);*/
        
            $attributes = '';
            $rules = $messages = [];

            if($module){
                switch ($module) {
                    case 'course_categories':
                        $rules = $this->getCourseCategoryRules();
                        $messages = $this->getCourseCategoryMessages();
                        break;
                    case 'courses_add':
                        $rules = $this->getCourseAddRules();
                        $messages = $this->getCourseAddMessages();
                        break;
                    case 'courses_basic':
                        $rules = $this->getCourseBasicRules();
                        $messages = $this->getCourseBasicMessages();
                        break;
                    case 'courses_pricing':
                        $rules = $this->getCoursePricingRules();
                        $messages = $this->getCoursePricingMessages();
                        break;
                    case 'courses_media':
                        $rules = $this->getCourseMediaRules();
                        $messages = $this->getCourseMediaMessages();
                        break;
                    case 'lessons_add':
                        $rules = $this->getLessonAddRules();
                        $messages = $this->getLessonAddMessages();
                        break;
                    case 'quizs_add':
                        $rules = $this->getQuizAddRules();
                        $messages = $this->getQuizAddMessages();
                        break;
                    case 'questions_add':
                        $rules = $this->getQuestionAddRules();
                        $messages = $this->getQuestionAddMessages();
                        break;
                    case 'exam_categories':
                        $rules = $this->getExamCategoryRules();
                        $messages = $this->getExamCategoryMessages();
                        break;
                    case 'exams_add':
                        $rules = $this->getExamAddRules();
                        $messages = $this->getExamAddMessages();
                        break;
                    case 'exams_questions_add':
                        $rules = $this->getExamQuestionAddRules();
                        $messages = $this->getExamQuestionAddMessages();
                        break;
                    case 'students_add':
                        $rules = $this->getStudentAddRules();
                        $messages = $this->getStudentAddMessages();
                        break;
                    case 'organizations_add':
                        $rules = $this->getOrganizationAddRules();
                        $messages = $this->getOrganizationAddMessages();
                        break;
                    case 'organization_roles_add':
                        $rules = $this->getOrganizationRoleAddRules();
                        $messages = $this->getOrganizationRoleAddMessages();
                        break;
                    case 'qbank_add':
                        $rules = $this->getQbankAddRules();
                        $messages = $this->getQbankAddMessages();
                        break;
                    case 'qbank_questions_add':
                        $rules = $this->getQbankQuestionAddRules();
                        $messages = $this->getQbankQuestionAddRules();
                        break;
                    case 'job_post_categories':
                        $rules = $this->getJobPostCategoryAddRules();
                        $messages = $this->getJobPostCategoryAddMessages();
                        break;
                    case 'job_posts_add':
                        $rules = $this->getJobPostAddRules();
                        $messages = $this->getJobPostAddMessages();
                        break;
                    case 'recorded_video_categories':
                        $rules = $this->getRecordedVideoCategoryAddRules();
                        $messages = $this->getRecordedVideoCategoryAddMessages();
                        break;
                    case 'recorded_videos_add':
                        $rules = $this->getRecordedVideoAddRules();
                        $messages = $this->getRecordedVideoAddMessages();
                        break;
                    case 'subscriptions_add':
                        $rules = $this->getSubscriptionAddRules();
                        $messages = $this->getSubscriptionAddMessages();
                        break;
                }
                if(empty($rules)){
                    return $attributes;
                }
            }else{
                return $attributes;
            }
            
            // Iterate through each rule for the field
            foreach ($rules[$field] ?? [] as $rule) {
                if(!is_string($rule)){
                    continue;
                }
                if (Str::contains($rule, 'required')) {
                    $attributes .= 'data-parsley-required="true" ';
                    $attributes .= 'data-parsley-required-message="' . ($messages["$field.required"] ?? 'This field is required.') . '" ';
                }
                if (Str::contains($rule, 'gt:')) {
                    $otherField = explode(':', $rule)[1];
                    $attributes .= 'data-parsley-gt="#' . $otherField . '" ';
                    $attributes .= 'data-parsley-gt-message="' . ($messages["$field.gt"] ?? "The value must be greater than $otherField.") . '" ';
                }                
                if (Str::contains($rule, 'gte:')) {
                    $otherField = explode(':', $rule)[1];
                    $attributes .= 'data-parsley-gte="#' . $otherField . '" ';
                    $attributes .= 'data-parsley-gte-message="' . ($messages["$field.gte"] ?? "The value must be greater than or equal to $otherField.") . '" ';
                }                
                if (Str::contains($rule, 'lt:')) {
                    $otherField = explode(':', $rule)[1];
                    $attributes .= 'data-parsley-lt="#' . $otherField . '" ';
                    $attributes .= 'data-parsley-lt-message="' . ($messages["$field.lt"] ?? "The value must be less than $otherField.") . '" ';
                }                
                if (Str::contains($rule, 'lte:')) {
                    $otherField = explode(':', $rule)[1];
                    $attributes .= 'data-parsley-lte="#' . $otherField . '" ';
                    $attributes .= 'data-parsley-lte-message="' . ($messages["$field.lte"] ?? "The value must be less than or equal to $otherField.") . '" ';
                }
                if (Str::contains($rule, 'email')) {
                    $attributes .= 'data-parsley-type="email" ';
                    $attributes .= 'data-parsley-type-message="' . ($messages["$field.email"] ?? 'Invalid email format.') . '" ';
                }
                if (Str::contains($rule, 'min:')) {
                    preg_match('/min:(\d+)/', $rule, $matches);
                    $attributes .= 'data-parsley-minlength="' . $matches[1] . '" ';
                    $attributes .= 'data-parsley-minlength-message="' . ($messages["$field.min"] ?? "Minimum length is {$matches[1]} characters.") . '" ';
                }
                if (Str::contains($rule, 'max:')) {
                    preg_match('/max:(\d+)/', $rule, $matches);
                    $attributes .= 'data-parsley-maxlength="' . $matches[1] . '" ';
                    $attributes .= 'data-parsley-maxlength-message="' . ($messages["$field.max"] ?? "Maximum length is {$matches[1]} characters.") . '" ';
                }
                if (Str::contains($rule, 'numeric')) {
                    $attributes .= 'data-parsley-type="number" ';
                    $attributes .= 'data-parsley-type-message="' . ($messages["$field.numeric"] ?? 'This field must be a number.') . '" ';
                }
                if (Str::contains($rule, 'digits:')) {
                    preg_match('/digits:(\d+)/', $rule, $matches);
                    $attributes .= 'data-parsley-length="[' . $matches[1] . ',' . $matches[1] . ']" ';
                    $attributes .= 'data-parsley-length-message="' . ($messages["$field.digits"] ?? "This field must be exactly {$matches[1]} digits.") . '" ';
                }
                if (Str::contains($rule, 'between:')) {
                    preg_match('/between:(\d+),(\d+)/', $rule, $matches);
                    $attributes .= 'data-parsley-range="[' . $matches[1] . ',' . $matches[2] . ']" ';
                    $attributes .= 'data-parsley-range-message="' . ($messages["$field.between"] ?? "This field must be between {$matches[1]} and {$matches[2]}.") . '" ';
                }
                if (Str::contains($rule, 'url')) {
                    $attributes .= 'data-parsley-type="url" ';
                    $attributes .= 'data-parsley-type-message="' . ($messages["$field.url"] ?? 'Please enter a valid URL.') . '" ';
                }
                if (Str::contains($rule, 'date')) {
                    $attributes .= 'data-parsley-type="date" ';
                    $attributes .= 'data-parsley-type-message="' . ($messages["$field.date"] ?? 'Please enter a valid date.') . '" ';
                }
                if (Str::contains($rule, 'confirmed')) {
                    $attributes .= 'data-parsley-equalto="#' . $field . '_confirmation" ';
                    $attributes .= 'data-parsley-equalto-message="' . ($messages["$field.confirmed"] ?? 'The confirmation does not match.') . '" ';
                }
                if (Str::contains($rule, 'image')) {
                    $attributes .= 'data-parsley-filetype="jpg,jpeg,png,gif" ';
                    $attributes .= 'data-parsley-filetype-message="' . ($messages["$field.image"] ?? 'The file must be an image (jpeg, png, or gif).') . '" ';
                }
                if (Str::contains($rule, 'in:')) {
                    preg_match('/in:([^|]+)/', $rule, $matches);
                    $validValues = implode(', ', explode(',', $matches[1]));
                    $attributes .= 'data-parsley-choices="' . $validValues . '" ';
                    $attributes .= 'data-parsley-choices-message="' . ($messages["$field.in"] ?? 'Please select a valid option.') . '" ';
                } 
                if (Str::contains($rule, 'exists:')) {
                    $attributes .= 'data-parsley-exists="' . str_replace(',', '.', $rule) . '" '; // Modify as needed
                    $attributes .= 'data-parsley-exists-message="' . ($messages["$field.exists"] ?? 'This value does not exist.') . '" ';
                }
                if (Str::contains($rule, 'string')) {
                    $attributes .= 'data-parsley-type="string" ';
                    $attributes .= 'data-parsley-type-message="' . ($messages["$field.string"] ?? 'This field must be a string.') . '" ';
                }
                /*if (Str::contains($rule, 'unique:')) {
                    $attributes .= 'data-parsley-unique="' . str_replace(',', '.', $rule) . '" '; // Modify as needed
                    $attributes .= 'data-parsley-unique-message="' . ($messages["$field.unique"] ?? 'This value must be unique.') . '" ';
                }*/
                if (Str::contains($rule, 'file')) {
                    $attributes .= 'data-parsley-file="true" ';
                    $attributes .= 'data-parsley-file-message="' . ($messages["$field.file"] ?? 'The uploaded file is not valid.') . '" ';
                }
                if (Str::contains($rule, 'present')) {
                    $attributes .= 'data-parsley-present="true" ';
                    $attributes .= 'data-parsley-present-message="' . ($messages["$field.present"] ?? 'This field must be present.') . '" ';
                }
                if (Str::contains($rule, 'mimes')) {
                    $mimes = explode(':', $rule)[1];
                    $attributes .= 'data-parsley-filemimetypes="' . str_replace(',', '|', $mimes) . '" ';
                    $attributes .= 'data-parsley-filemimetypes-message="Invalid file type. Allowed types are: ' . str_replace(',', ', ', $mimes) . '." ';
                }
                if (Str::contains($rule, 'decimal')) {
                    preg_match('/decimal:(\d+),(\d+)/', $rule, $matches);
                    $minDecimals = $matches[1] ?? 0;
                    $maxDecimals = $matches[2] ?? 2;
                    $attributes .= 'data-parsley-pattern="^\d+(\.\d{' . $minDecimals . ',' . $maxDecimals . '})?$" ';
                    $attributes .= 'data-parsley-pattern-message="Enter a number with up to ' . $maxDecimals . ' decimal places." ';
                }
            }
        
            return $attributes;
        });
    }
}
