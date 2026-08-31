<?php

use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExamCategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\JobPostCategoryController;
use App\Http\Controllers\Admin\JobPostController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\QbankController;
use App\Http\Controllers\Admin\RecordedVideoCategoryController;
use App\Http\Controllers\Admin\RecordedVideoController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,organization'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (){
        //Route::pattern('course', '[0-9]+');  // Global validation for course parameter

        Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');
        Route::middleware(['role:admin'])->group(function (){
            Route::get('settings', [SettingController::class, 'settings'])->name('settings');
            Route::get('settings/social-media', [SettingController::class, 'settings_social_media'])->name('settings.social-media');
            Route::get('settings/certificate', [SettingController::class, 'settings_certificate'])->name('settings.certificate');
            Route::get('settings/razorpay', [SettingController::class, 'settings_razorpay'])->name('settings.razorpay');
            Route::get('settings/social-login', [SettingController::class, 'settings_social_login'])->name('settings.social-login');
            Route::get('settings/{id}', [SettingController::class, 'edit_setting'])->name('settings.edit');
            Route::put('settings/{id}', [SettingController::class, 'update_setting'])->name('settings.update');
        });

        Route::middleware(['role:organization'])->group(function (){
            Route::get('organization-settings', [SettingController::class, 'edit_organization_setting'])->name('settings.organization');
            Route::put('organization-settings', [SettingController::class, 'update_organization_setting'])->name('settings.organization-update');
        });

        Route::resource('categories', CourseCategoryController::class)->names('course_categories');
        Route::put('categories/activate/{id}/{status}', [CourseCategoryController::class, 'activate'])->where('status', '1|0')->name('course_categories.activate');
        Route::resource('courses', CourseController::class)->names('courses');
        Route::get('courses/{course}/edit/{action?}', [CourseController::class, 'edit'])
            ->where('action', 'basic|curriculum|pricing|info|media|seo')
            ->name('courses.custom-edit');

        Route::delete('courses/gallery-images/{galleryImage}', [CourseController::class, 'deleteGalleryImage'])->name('courses.gallery-images.destroy');
        Route::put('courses/gallery-images/{galleryImage}/alt-text',[CourseController::class,'updateGalleryImageAltText'])->name('courses.gallery-images.alt-text');

        Route::controller(CourseController::class)->group(function () {
            Route::put('courses/activate/{courseId}/{status}', 'activate')->name('courses.activate');
            Route::get('courses/ratings/{courseId}', 'ratings')->name('courses.ratings');
            Route::get('courses/enrolments/{courseId}', 'enrolments')->name('courses.enrolments');

            Route::post('courses/sections/create', 'create_section')->name('courses.sections.create');
            Route::get('courses/sections/delete/{courseId}/{id}', 'delete_section')->name('courses.sections.delete');
            Route::post('courses/sections/sort', 'sort_sections')->name('courses.sections.sort');
            
            Route::get('courses/{courseId}/create-lesson', 'create_lesson')->name('courses.lessons.create');
            Route::get('courses/{courseId}/edit-lesson/{id}', 'edit_lesson')->name('courses.lessons.edit');
            Route::post('courses/lessons/save', 'save_lesson')->name('courses.lessons.save');
            Route::get('courses/lessons/delete/{courseId}/{sectionId}/{id}', 'delete_lesson')->name('courses.lessons.delete');
            Route::post('courses/lessons/sort', 'sort_lessons')->name('courses.lessons.sort');
            Route::get('courses/{courseId}/quiz-results/{lessonId}', 'quiz_results')->name('courses.quiz-results');
            Route::get('courses/{courseId}/quiz-results/{lessonId}/details/{userId}', 'quiz_result_details')->name('courses.quiz-result-details');
            
            Route::get('courses/{courseId}/create-quiz', 'create_quiz')->name('courses.quizs.create');
            Route::get('courses/{courseId}/edit-quiz/{id}', 'edit_quiz')->name('courses.quizs.edit');
            Route::post('courses/quizs/save', 'save_quiz')->name('courses.quizs.save');
            
            Route::get('courses/{courseId}/questions/{lessonId}', 'questions')->name('courses.questions');
            Route::get('courses/{courseId}/create-question/{lessonId}', 'create_question')->name('courses.questions.create')->middleware('sanitize');
            //Route::get('courses/{courseId}/edit-question/{id}', 'edit_question')->name('courses.questions.edit');
            Route::post('courses/questions/save', 'save_question')->name('courses.questions.save');
            Route::get('courses/questions/delete/{courseId}/{sectionId}/{lessonId}/{id}', 'delete_question')->name('courses.questions.delete');
            
            Route::get('layoutless', 'layoutless')->name('courses.layoutless');
        });
        
        Route::resource('exam-categories', ExamCategoryController::class)->names('exam_categories');
        Route::put('exam-categories/activate/{id}/{status}', [ExamCategoryController::class, 'activate'])->where('status', '1|0')->name('exam_categories.activate');
        Route::controller(ExamController::class)->group(function () {
            Route::resource('exams', ExamController::class)->names('exams');
            Route::get('exams/ratings/{courseId}', 'ratings')->name('exams.ratings');

            Route::put('exams/{examId}/activate/{status}', 'activate')->name('exams.activate');
            Route::get('exams/{examId}/import-questions', 'import_questions')->name('exams.import-questions');
            Route::post('exams/{examId}/upload', 'upload_questions')->name('exams.upload_questions');
            Route::get('exams/{examId}/review-questions', 'review_questions')->name('exams.review_questions');
            Route::get('exams/{examId}/delete-import-questions/{id}', 'delete_import_questions')->name('exams.delete-import-questions');
            Route::post('exams/{examId}/disable-bulk-questions', 'disable_bulk_questions')->name('exams.disable_bulk_questions');
            Route::post('exams/{examId}/save-bulk-questions', 'save_bulk_questions')->name('exams.save_bulk_questions');

            Route::get('exams/{examId}/results', 'results')->name('exams.results');
            Route::get('exams/{examId}/results/{userId}/details', 'result_details')->name('exams.results.details');
            Route::get('exams/{examId}/results/{userId}/details/{resultId}/answers', 'answers')->name('exams.results.answers');
            
            Route::get('exams_bulk', 'bulk')->name('exams.bulk');
        });

        Route::controller(QbankController::class)->group(function () {
            Route::resource('qbank', QbankController::class)->names('qbank');

            Route::put('qbank/{qbankId}/activate/{status}', 'activate')->name('qbank.activate');
            Route::get('qbank/{qbankId}/import-questions', 'import_questions')->name('qbank.import-questions');
            Route::post('qbank/{qbankId}/upload', 'upload_questions')->name('qbank.upload_questions');
            Route::get('qbank/{qbankId}/review-questions', 'review_questions')->name('qbank.review_questions');
            Route::get('qbank/{qbankId}/delete-import-questions/{id}', 'delete_import_questions')->name('qbank.delete-import-questions');
            Route::post('qbank/{qbankId}/disable-bulk-questions', 'disable_bulk_questions')->name('qbank.disable_bulk_questions');
            Route::post('qbank/{qbankId}/save-bulk-questions', 'save_bulk_questions')->name('qbank.save_bulk_questions');

            Route::get('qbank/{qbankId}/questions', 'questions')->name('qbank.questions');
            Route::get('qbank/{qbankId}/create-question', 'create_question')->name('qbank.questions.create')->middleware('sanitize');
            Route::get('qbank/{qbankId}/edit-question/{id}', 'edit_question')->name('qbank.questions.edit');
            Route::post('qbank/{qbankId}/questions/save', 'save_question')->name('qbank.questions.save');
            Route::put('qbank/{qbankId}/questions/{questionId}/activate/{status}', 'activate_question')->name('qbank.questions.activate');
            Route::delete('qbank/{qbankId}/questions/{questionId}/delete', 'delete_question')->name('qbank.questions.delete');
        });

        Route::controller(PaymentController::class)->group(function () {
            Route::get('payments', 'index')->name('payments');
        });

        Route::controller(RoleController::class)->group(function () {
            Route::get('roles/organization', 'organizations')->name('roles.organization');
            Route::get('roles/organization/create', 'create_organization_role')->name('roles.organization.create');
            Route::get('roles/organization/{id}/edit', 'edit_organization_role')->name('roles.organization.edit');
            Route::post('roles/organization/save', 'save_organization_role')->name('roles.organization.save');
            Route::delete('roles/organization/{id}', 'delete_organization_role')->name('roles.organization.delete');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('users/organizations', 'organizations')->name('users.organizations');
            Route::get('users/admin', 'admin_users')->name('users.admin');
            Route::get('users/organization', 'organization_users')->name('users.organization');
            Route::get('users/student', 'student_users')->name('users.student');
            Route::put('users/admin/{userId}/activate/{status}', 'activate')->name('users.staff.activate');
            Route::put('users/student/{userId}/activate/{status}', 'activate')->name('users.student.activate');
            Route::put('users/organization/{userId}/activate/{status}', 'activate')->name('users.organization.activate');

            Route::get('users/student/import', 'import_students')->name('users.student.import');
            Route::post('users/student/upload', 'upload_students')->name('users.student.upload');
            Route::get('users/student/review/{grouping}', 'review_students')->name('users.student.review');
            Route::post('users/student/save-bulk/{grouping}', 'save_bulk_students')->name('users.student.save_bulk');

            Route::get('users/student/create', 'create_student')->name('users.student.create');
            Route::get('users/student/{id}/edit', 'edit_student')->name('users.student.edit');
            Route::post('users/student/save', 'save_student')->name('users.student.save');

            Route::get('users/organization/create', 'create_organization')->name('users.organization.create');
            Route::get('users/organization/{id}/edit', 'edit_organization')->name('users.organization.edit');
            Route::post('users/organization/save', 'save_organization')->name('users.organization.save');

            Route::get('users/organization/{organizationId}/staff/create', 'create_organization_staff')->name('users.organization.staff.create');
            Route::get('users/organization/{organizationId}/staff/{id}/edit', 'edit_organization_staff')->name('users.organization.staff.edit');
            Route::post('users/organization/{organizationId}/save_staff', 'save_organization_staff')->name('users.organization.staff.save');
        });

        Route::resource('job-post-categories', JobPostCategoryController::class)->names('job-post-categories');
        Route::put('job-post-categories/activate/{id}/{status}', [JobPostCategoryController::class, 'activate'])->where('status', '1|0')->name('job-post-categories.activate');
        Route::controller(JobPostController::class)->group(function () {
            Route::resource('job-posts', JobPostController::class)->names('job-posts');
            Route::put('job-posts/{jobPostId}/activate/{status}', 'activate')->name('job-posts.activate');
            Route::get('job-posts/ratings/{jobPostId}', 'ratings')->name('job-posts.ratings');
            Route::get('job-posts/enrolments/{jobPostId}', 'enrolments')->name('job-posts.enrolments');
        });

        Route::resource('recorded-video-categories', RecordedVideoCategoryController::class)->names('recorded-video-categories');
        Route::put('recorded-video-categories/activate/{id}/{status}', [RecordedVideoCategoryController::class, 'activate'])->where('status', '1|0')->name('recorded-video-categories.activate');
        Route::controller(RecordedVideoController::class)->group(function () {
            Route::resource('recorded-videos', RecordedVideoController::class)->names('recorded-videos');
            Route::put('recorded-videos/{recordedVideoId}/activate/{status}', 'activate')->name('recorded-videos.activate');
            Route::get('recorded-videos/ratings/{recordedVideoId}', 'ratings')->name('recorded-videos.ratings');
            Route::get('recorded-videos/enrolments/{recordedVideoId}', 'enrolments')->name('recorded-videos.enrolments');
        });

        Route::controller(SubscriptionController::class)->group(function () {
            Route::resource('subscriptions', SubscriptionController::class)->except(['index', 'show'])->names('subscriptions')->middleware(['role:admin']);
            Route::get('subscriptions', 'index')->name('subscriptions.index');
            Route::get('subscriptions/{subscription}', 'show')->name('subscriptions.show');
            Route::get('subscriptions/pay/{id}', 'pay')->name('subscriptions.pay');
            Route::put('subscriptions/pay/{id}', 'save_payment')->name('subscriptions.save-payment');
            Route::get('subscriptions/pay/razorpay/{id}', 'pay_razorpay')->name('subscriptions.pay-razorpay');
            Route::put('subscriptions/approve/{id}', 'approve')->name('subscriptions.approve');
            Route::get('subscriptions/setup/{organizationId}', 'setup')->name('subscriptions.setup');
            Route::post('subscriptions/setup/{organizationId}', 'save_setup')->name('subscriptions.save-setup');
        });

        Route::controller(AjaxController::class)->group(function () {
            Route::post('verify_razorpay_payment', 'verify_razorpay_payment')->name('ajax.verify_razorpay_payment');
        });
        
    });
