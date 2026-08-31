<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::controller(ApiController::class)->group(function () {
    Route::get('/get_settings', 'get_settings');

    Route::post('/login', 'login')->name('api.login');
    Route::post('/register', 'register')->name('api.register');
    Route::post('/social-login', 'social_login')->name('api.social-login');
    Route::post('/logout', 'logout')->name('api.logout');
    Route::post('/forgot-password', 'forgot_password')->name('api.forgot-password')->middleware('throttle:15,1'); // 5 requests per minute
    Route::post('/reset-password', 'reset_password')->name('api.reset-password');

    Route::get('/get_categories', 'get_categories');
    Route::get('/get_categories_tree', 'get_categories_tree');
    Route::get('/get_all_categories', 'get_all_categories');
    Route::get('/get_parent_categories', 'get_parent_categories');
    Route::get('/get_category_details/{id?}', 'get_category_details');
    Route::get('/get_sub_categories/{id?}', 'get_sub_categories');

    Route::get('/get_courses', 'get_courses');
    Route::get('/get_course_details/{id?}', 'get_course_details');

    Route::get('/get_exam_categories', 'get_exam_categories');
    Route::get('/get_exam_category_details/{id?}', 'get_exam_category_details');
    
    Route::get('/get_exams', 'get_exams');
    Route::get('/get_exam_details/{id?}', 'get_exam_details');

    Route::get('/get_recorded_video_categories', 'get_recorded_video_categories');
    Route::get('/get_recorded_video_category_details/{id?}', 'get_recorded_video_category_details');
    
    Route::get('/get_recorded_videos', 'get_recorded_videos');
    Route::get('/get_recorded_video_details/{id?}', 'get_recorded_video_details');

    Route::get('/get_job_post_categories', 'get_job_post_categories');
    Route::get('/get_job_post_category_details/{id?}', 'get_job_post_category_details');
    
    Route::get('/get_job_posts', 'get_job_posts');
    Route::get('/get_job_post_details/{id?}', 'get_job_post_details');

    Route::middleware(['auth:sanctum'])->group(function (){
        Route::get('/get_profile', 'get_profile');
        Route::post('/update_profile', 'update_profile');
        Route::post('/update_password', 'update_password');
        Route::delete('/delete_account', 'delete_account');
        
        Route::get('/get_dashboard', 'get_dashboard');
        Route::get('/get_my_exams', 'get_my_exams');
        Route::get('/get_my_courses', 'get_my_courses');
        Route::get('/get_my_recorded_videos', 'get_my_recorded_videos');
        Route::get('/get_my_job_posts', 'get_my_job_posts');
        
        Route::get('/get_course_certificate/{id?}', 'get_course_certificate');
        Route::get('/get_exam_certificate/{id?}', 'get_exam_certificate');
        
        Route::post('/exams/enroll/{id}', 'exam_enroll');
        Route::post('/exams/questions/{id}', 'exam_questions');
        Route::post('/exams/submit_answers/{id}', 'exam_submit_answers');
        Route::get('/exams/results/{id}', 'get_exam_results');
        Route::get('/exams/results/{examId}/details/{resultId}', 'get_exam_result_details');
        
        Route::post('/courses/enroll/{id}', 'course_enroll');
        Route::post('/courses/{courseId}/progress/{lessonId}', 'course_progress');
        Route::post('/courses/{courseId}/submit_answers/{lessonId}', 'course_quiz_submit_answers');
        Route::post('/courses/{courseId}/results/{lessonId}', 'get_course_quiz_results');
        Route::post('/courses/{courseId}/results/{lessonId}/details/{resultId}', 'get_course_quiz_results_details');
        
        Route::post('/job_posts/enroll/{id}', 'job_post_enroll');
        
        Route::post('/recorded_videos/enroll/{id}', 'recorded_video_enroll');

        Route::get('/get_wishlist', 'wishlist');
        Route::post('/wishlist/add', 'wishlist_add');
        Route::post('/wishlist/remove', 'wishlist_remove');
        
        Route::get('/get_cart', 'cart');
        Route::post('/cart/add', 'cart_add');
        Route::post('/cart/remove', 'cart_remove');
        Route::post('/cart/checkout', 'cart_checkout');

        Route::get('/get_ratings', 'get_ratings');
        Route::post('/ratings/add', 'ratings_add');

        Route::post('/logout', 'logout');
    });
});

