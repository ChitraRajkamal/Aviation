<?php

namespace App\Http\Controllers;

use App\Mail\CertificateMail;
use App\Models\Cart;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnroll;
use App\Models\Exam;
use App\Models\ExamEnroll;
use App\Models\JobPost;
use App\Models\JobPostEnroll;
use App\Models\Payment;
use App\Models\Rating;
use App\Models\RecordedVideo;
use App\Models\RecordedVideoEnroll;
use App\Models\User;
use App\Models\Wishlist;
use Artisan;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Mail;
use Storage;

class HomeController extends Controller
{
    public $userId = 0;
    public function __construct()
    {
        $this->userId = auth()->id();
    }
    public function create_symlink(){
        try {
            Artisan::call('storage:link');
            return 'Symlink created successfully';
        } catch (Exception) {
            return "Storage Link already exists";
        }
    }

    public function index(){
        /*$categories = CourseCategory::where([
            ['status', 1],
            ['parent_id', 0]
        ])->take(12)->withCount('courses')->get();*/
        //$path = CourseCategory::getAllCourses(45, ' ---> ');
        
        $categories = CourseCategory::getCategoryWithCourseCount();
        $categories_n_courses = CourseCategory::getCategoryWithCourses()->take(3);

        return view('frontend.home', compact('categories', 'categories_n_courses'));
    } 

    public function about_us(){
        return view('frontend.about-us');
    }

    public function contact_us(){
        return view('frontend.contact-us');
    }

    public function send_contact_message(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['required', 'email:rfc,dns', 'max:100'],
            'course' => [
                'required',
                Rule::in([
                    'Cabin Crew Training',
                    'Pilot Training',
                    'Airport Operations',
                    'Travel & Tourism Management',
                    'Drone Pilot Training',
                    'Air Cargo Operations'
                ])
            ],
            'message' => ['required', 'string', 'max:1200'],
        ]);

        Mail::send('emails.enquiry', compact('data'), function ($message) {
            $message->to(lms_setting('contact_email'))
                    ->subject('New Career Counselling Enquiry');
        });

        return redirect()->back()->with(
            'alert',
            generate_alert('Your enquiry has been submitted successfully.')
        );
    }

    public function dashboard(){
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
        return view('frontend.dashboard', compact('exam_enrolled', 'exam_attended', 'exams', 'course_enrolled', 'course_completed', 'courses'));
    }

    public function my_profile(){
        $user = auth()->user();
        return view('frontend.my-profile', compact('user'));
    }

    public function my_exams(){
        $exams = ExamEnroll::where([
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->paginate(lms_setting('frontend_pagination_size'));
        return view('frontend.my-exams', compact('exams'));
    }

    public function my_courses(){
        $courses = CourseEnroll::where([
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->paginate(lms_setting('frontend_pagination_size'));
        return view('frontend.my-courses', compact('courses'));
    }

    public function my_recorded_videos(){
        $enrolls = RecordedVideoEnroll::where([
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->paginate(lms_setting('frontend_pagination_size'));
        return view('frontend.my-recorded-videos', compact('enrolls'));
    }

    public function my_job_posts(){
        $enrolls = JobPostEnroll::where([
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->paginate(lms_setting('frontend_pagination_size'));
        return view('frontend.my-job-posts', compact('enrolls'));
    }

    public function my_wishlist(){
        $wishlist = Wishlist::where([
            ['status', 1],
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->paginate(lms_setting('frontend_pagination_size'));
        return view('frontend.my-wishlist', compact('wishlist'));
    }

    public function my_ratings(){
        $ratings = Rating::where([
            ['status', 1],
            ['user_id', $this->userId]
        ])->orderByDesc('created_at')->paginate(lms_setting('frontend_pagination_size'));
        return view('frontend.my-ratings', compact('ratings'));
    }

    public function my_cart(Request $request){
        $payment = false;
        if($request->pay){
            $paymentId = $request->pay;
            $payment = Payment::find($paymentId);
            if(!$payment || $payment->status == 'captured' || $payment->status == 'disabled'){
                abort(404, 'payment not found');
            }
            $cart_ids = $payment->remarks ? json_decode($payment->remarks) : [];
            $cart = Cart::whereIn('id', $cart_ids)->orderByDesc('created_at')->get();
        }else{
            $cart = Cart::where([
                ['is_paid', 0],
                ['user_id', $this->userId]
            ])->orderByDesc('created_at')->get();
        }
        return view('frontend.my-cart', compact('cart', 'payment'));
    }

    public function cart_save(string $type = null, int $type_id = null)
    {
        if(!$type || !$type_id || !$this->userId) return redirect()->back()->with('alert', generate_alert(__('Missing required params or sesson'), 'danger'));
        if($type == 'course'){
            $course = Course::find($type_id);
            if(!$course){
                return redirect()->back()->with('alert', generate_alert(__('Course not found'), 'danger'));
            }
            $course_enrolled = CourseEnroll::where([
                ['user_id', $this->userId],
                ['course_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($course_enrolled){
                return redirect()->back()->with('alert', generate_alert(__('Course already enrolled'), 'danger'));
            }
        }else if($type == 'exam'){
            $exam = Exam::find($type_id);
            if(!$exam){
                return redirect()->back()->with('alert', generate_alert(__('Exam not found'), 'danger'));
            }
            $exam_enrolled = ExamEnroll::where([
                ['user_id', $this->userId],
                ['exam_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($exam_enrolled){
                return redirect()->back()->with('alert', generate_alert(__('Exam already enrolled'), 'danger'));
            }
        }else if($type == 'recorded-video'){
            $recordedVideo = RecordedVideo::find($type_id);
            if(!$recordedVideo){
                return redirect()->back()->with('alert', generate_alert(__('Recorded Video not found'), 'danger'));
            }
            $recorded_video_enrolled = RecordedVideoEnroll::where([
                ['user_id', $this->userId],
                ['recorded_video_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($recorded_video_enrolled){
                return redirect()->back()->with('alert', generate_alert(__('Recorded Video already enrolled'), 'danger'));
            }
        }else if($type == 'job-post'){
            $jobPost = JobPost::find($type_id);
            if(!$jobPost){
                return redirect()->back()->with('alert', generate_alert(__('Job Post not found'), 'danger'));
            }
            $job_post_enrolled = JobPostEnroll::where([
                ['user_id', $this->userId],
                ['job_post_id', $type_id],
                ['is_enrolled', true]
            ])->exists();
            if($job_post_enrolled){
                return redirect()->back()->with('alert', generate_alert(__('Job Post already enrolled'), 'danger'));
            }
        }
        
        $where = [
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        $data = Cart::where($where)->select('id')->first();
        if(!$data){
            if((isset($course) && !$course->is_paid) && (isset($exam) && !$exam->is_paid)){
                return redirect()->back()->with('alert', generate_alert(__('No payment is required for this'), 'danger'));
            }
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
            return redirect()->back()->with('alert', generate_alert(__('Added to cart')));
        }else{
            $data->delete();
            return redirect()->back()->with('alert', generate_alert(__('Removed from cart')));
        }
    }

    public function cart_remove(string $type, int $type_id)
    {
        if(!$type || !$type_id || !$this->userId) return redirect()->back()->with('alert', generate_alert(__('Missing required params or sesson'), 'danger'));
        $where = [
            ['is_paid', 0],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        if(Cart::where($where)->delete()){
            return redirect()->back()->with('alert', generate_alert(__('Removed from cart')));
        }
        return redirect()->back()->with('alert', generate_alert(__('Selected item is not availble in cart')));
    }

    public function cart_create_order(){
        $oldPayment = Payment::where([
            ['user_id', $this->userId],
            ['type', 'cart'],
            ['type_id', 0],
            ['status', 'pending']
        ])->orderByDesc('id')->first();
        $amount = Cart::where([
            ['is_paid', 0],
            ['user_id', $this->userId]
        ])->sum('amount');
        if(!$amount){
            return redirect()->back()->with('alert', generate_alert(__('Please add at least one course / exam to checkout. Amount should not be zero'), 'danger'));
        }
        if($oldPayment && $amount == $oldPayment->amount){
            return redirect(route('my-cart') . "?pay=$oldPayment->id");
        }
        Payment::where([
            ['user_id', $this->userId],
            ['type', 'cart'],
            ['type_id', 0],
            ['status', 'pending']
        ])->update(['status' => 'disabled']);
        $response = lms_create_razorpay_order('INR', $amount, 'cart');
        
        $cart_ids = Cart::where([
            ['is_paid', 0],
            ['user_id', $this->userId]
        ])->pluck('id')->toArray();
        $payment = [
            'user_id' => $this->userId,
            'type' => 'cart',
            'type_id' => 0,
            'date' => now(),
            'amount' => $amount,
            'order_id' => $response->id,
            'status' => 'pending',
            'remarks' => json_encode($cart_ids),
            'source' => 'website',
            'request_data' => json_encode($response),
        ];
        $newPayment = Payment::create($payment);
        Cart::where([
            ['status', 1],
            ['user_id', $this->userId]
        ])->update(['status' => 0, 'payment_id' => $newPayment->id]);
        return redirect(route('my-cart') . "?pay=$newPayment->id");
    }   

    public function rating_save(string $type, int $type_id, Request $request){
        // Ensure valid type and existence of the related record
        if (!in_array($type, ['course', 'exam', 'recorded-video', 'job-post']) || !$this->validateType($type, $type_id)) {
            throw ValidationException::withMessages(['type' => 'Invalid ' . ucfirst($type) . ' provided.']);
        }
        
        $data = $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'message' => 'required|string|max:1200',
        ]);
        $check = Rating::where([
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
            ['status', 1]
        ])->exists();
        if(!$check){
            $data['user_id'] = $this->userId;
            $data['type'] = $type;
            $data['type_id'] = $type_id;
            $data['status'] = 1;
            Rating::create($data);
        }
        return redirect()->back()->with('alert', generate_alert(__('Ratings saved')));
    }
    
    private function validateType(string $type, int $type_id): bool
    {
        return match ($type) {
            'course'            => Course::where('id', $type_id)->exists(),
            'exam'              => Exam::where('id', $type_id)->exists(),
            'recorded-video'    => RecordedVideo::where('id', $type_id)->exists(),
            'job-post'          => JobPost::where('id', $type_id)->exists(),
            default             => false,
        };
    }

    public function google_auth(){
        return view('frontend.google-auth');
    }

    public function google_auth_verify(Request $request){
        $token = $request->input('t');
		$status = 'error';
        $message = 'Error occurred while verifying data from google server. Please try again';
		$google_user = file_get_contents("https://www.googleapis.com/oauth2/v2/userinfo?fields=name%2Cemail%2Cid%2Cpicture&access_token=$token");
        if(!empty($google_user)){
			$data = json_decode($google_user);
			$email = @$data->email;
            $user = User::where('email', $email)->first();
            if($user){
                if($user->role != 'student'){
                    lms_return_json(['status' => $status, 'message' => "There is already a {$user->role} account with this Email ID"]);
                    exit;
                }
                $user->first_name = explode(' ', $data->name)[0];
                $user->last_name = explode(' ', $data->name)[1] ?? '';
                $user->image = $data->picture ?? '';
                $user->google_id = $data->id;
                $user->google_token = $token ?? null;
                $user->login_from = 'google';
                $user->save();
            }else{
                $password = lms_random_password();
                $user_data = [
                    'first_name' => explode(' ', $data->name)[0],
                    'last_name' => explode(' ', $data->name)[1] ?? '',
                    'role' => 'student',
                    'email' => $email,
					'image' => $data->picture ?? '',
                    'google_id' => $data->id,
                    'google_token' => $token,
                    'login_from' => 'google',
                    'password' => $password,
                ];
                $user = User::create($user_data);
            }
            $status = 'success';
            $message = 'Login is successful';
		}
		lms_return_json(['status' => $status, 'message' => $message]);
    }

    public function login_by_token(Request $request){
        $token = $request->input('login_token');
        if(!$token){
            abort(404);
        }
        $user = User::orWhere('google_token', $token)->orWhere('facebook_token', $token)->first();
        if(!$user){
            abort(404);
        }
        Auth::login($user);
        $request->session()->regenerate();
        return to_route('home')->with('alert', generate_alert(__('Login is successful')));
    }

    public function facebook_auth_verify(Request $request){
        $token = $request->input('t');
		$status = 'error';
        $message = 'Error occurred while verifying data from google server. Please try again';
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://graph.facebook.com/v22.0/me?access_token=$token&fields=id%2Cname%2Cemail%2Cpicture",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$facebook_user = curl_exec($curl);

		curl_close($curl);

		if(!empty($facebook_user)){
            $status = 'success';
            $message = 'Login is successful';
			$data = json_decode($facebook_user);
			$email = $data->email ?? '';
			$picture = $data->picture->data->url ?? '';
            $user = User::where('email', $email)->first();
            if($user){
                $user->first_name = explode(' ', $data->name)[0];
                $user->last_name = explode(' ', $data->name)[1] ?? '';
                $user->image = $picture;
                $user->facebook_id = $data->id;
                $user->facebook_token = $token ?? null;
                $user->login_from = 'facebook';
                $user->save();
            }else{
                $password = lms_random_password();
                $user_data = [
                    'first_name' => explode(' ', $data->name)[0],
                    'last_name' => explode(' ', $data->name)[1] ?? '',
                    'role' => 'student',
                    'email' => $email,
					'image' => $picture,
                    'facebook_id' => $data->id,
                    'facebook_token' => $token,
                    'login_from' => 'facebook',
                    'password' => $password,
                ];
                $user = User::create($user_data);
            }
		}
		lms_return_json(['status' => $status, 'message' => $message]);
    }

    public function generate_certificate_preview()
    {
        $data = [
            ...lms_settings('certificate'),
            'student_name' => 'Hussain Badusha',
            'course_name' => 'Nice Course',
            'date' => now()->format('d M Y'),
        ];
        return view('frontend.certificate.testcert', $data);
    }

    public function generate_certificate()
    {
        $pdf = Pdf::loadView('frontend/certificate/testcert', [
            ...lms_settings('certificate'),
            'student_name' => 'Hussain Badusha',
            'course_name' => 'Nice Course',
            'date' => now()->format('d M Y'),
        ]);
    
        $path = 'certificates/' . 1 . '_' . 2 . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        return '';

        $studentEmail = 'hussainmh39@gmail.com';
        $certificatePath = 'storage/certificates/1_2.pdf';
        $data = ['student_name' => 'Hussain Badusha', 'course_name' => 'Laravel Masterclass', 'date' => date('Y-m-d')];
        $ss = Mail::to($studentEmail)->send(new CertificateMail($certificatePath, $data));
        dd($ss);

        $pdf = Pdf::loadView('frontend/certificate/testcert', ['student_name' => 'Hussain Badusha', 'course_name' => 'Laravel Masterclass', 'date' => date('Y-m-d')]);
        return $pdf->download('certificate.pdf');
    }

    public function change_password(){
        return view('frontend.change-password');
    }

    public function edit_profile(Request $request){
        $user = $request->user();
        return view('frontend.edit-profile', compact('user'));
    }

    public function gallery(){
        return view('frontend.gallery');
    }

    public function download_brochure(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['required', 'email:rfc,dns', 'max:100'],
        ]);

        Mail::send('emails.brochure_enquiry', compact('data'), function ($message) {
            $message->to(lms_setting('contact_email'))
                    ->subject('New Brochure Download Request');
        });

        return response()->json([
            'success' => true,
            'download_url' => asset('assets/ffa-brochure.pdf')
        ]);
    }
}
