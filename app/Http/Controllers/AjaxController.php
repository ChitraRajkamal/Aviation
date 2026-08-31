<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\CourseEnroll;
use App\Models\Exam;
use App\Models\ExamEnroll;
use App\Models\JobPost;
use App\Models\JobPostEnroll;
use App\Models\Payment;
use App\Models\RecordedVideo;
use App\Models\RecordedVideoEnroll;
use App\Models\User;
use App\Models\Wishlist;
use DB;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public $userId = 0;
    public function __construct()
    {
        $this->userId = auth()->id();
    }
    /**
     * Display a listing of the resource.
     */
    public function search_students(Request $request)
    {
        $data = User::where('status', 1)->whereIn('role', lms_student_role())
                ->where(function ($query) use ($request) {
                    $searchTerm = $request->input('query');
                    $query->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) AS text"))
                ->get();
        $result = [
            'status' => 'success',
            'items' => $data
        ];
        return response()->json($result);
    }
    
    private function update_razorpay_payment($payment, $razorpay_payment)
    {
        $payment->status = 'captured';
        $payment->response_data = json_encode($razorpay_payment);
        $payment->save(); 
        if($payment->type == 'exam'){
            $check = ExamEnroll::where([
                ['user_id', $payment->user_id],
                ['exam_id', $payment->type_id]
            ])->exists();
            if(!$check){
                $exam = Exam::find($payment->type_id);
                ExamEnroll::create([
                    'user_id' => $payment->user_id,
                    'exam_id' => $payment->type_id,
                    'is_enrolled' => true,
                    'organization_id' => $exam->organization_id,
                ]);
                DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", [$payment->type, $payment->type_id]);
            }
        }else if($payment->type == 'course'){
            $check = CourseEnroll::where([
                ['user_id', $payment->user_id],
                ['course_id', $payment->type_id]
            ])->exists();
            if(!$check){
                $course = Course::find($payment->type_id);
                CourseEnroll::create([
                    'user_id' => $payment->user_id,
                    'course_id' => $payment->type_id,
                    'is_enrolled' => true,
                    'organization_id' => $course->organization_id,
                    'lesson_ids' => [],
                ]);
                DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", [$payment->type, $payment->type_id]);
            }
        }else if($payment->type == 'recorded-video'){
            $check = RecordedVideoEnroll::where([
                ['user_id', $payment->user_id],
                ['recorded_video_id', $payment->type_id]
            ])->exists();
            if(!$check){
                $recordedVideo = RecordedVideo::find($payment->type_id);
                RecordedVideoEnroll::create([
                    'user_id' => $payment->user_id,
                    'recorded_video_id' => $payment->type_id,
                    'is_enrolled' => true,
                    'organization_id' => $recordedVideo->organization_id,
                ]);
                //DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", [$payment->type, $payment->type_id]);
            }
        }else if($payment->type == 'job-post'){
            $check = JobPostEnroll::where([
                ['user_id', $payment->user_id],
                ['job_post_id', $payment->type_id]
            ])->exists();
            if(!$check){
                $recordedVideo = JobPost::find($payment->type_id);
                JobPostEnroll::create([
                    'user_id' => $payment->user_id,
                    'job_post_id' => $payment->type_id,
                    'is_enrolled' => true,
                    'organization_id' => $recordedVideo->organization_id,
                ]);
                //DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", [$payment->type, $payment->type_id]);
            }
        }else if($payment->type == 'cart'){
            $cart_ids = json_decode($payment->remarks);
            $cart = Cart::whereIn('id', $cart_ids)->get();
            if($cart){
                foreach ($cart as $k => $c) {
                    $c->is_paid = true;
                    $c->save();
                    if($c->type == 'course'){
                        $course = Course::find($c->type_id);
                        CourseEnroll::create([
                            'user_id' => $payment->user_id,
                            'course_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $course->organization_id,
                            'lesson_ids' => [],
                        ]);
                    }else if($c->type == 'exam'){
                        $exam = Exam::find($c->type_id);
                        ExamEnroll::create([
                            'user_id' => $payment->user_id,
                            'exam_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $exam->organization_id,
                        ]);
                    }else if($c->type == 'recorded-video'){
                        $recordedVideo = RecordedVideo::find($c->type_id);
                        RecordedVideoEnroll::create([
                            'user_id' => $payment->user_id,
                            'recorded_video_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $recordedVideo->organization_id,
                        ]);
                    }else if($c->type == 'job-post'){
                        $recordedVideo = JobPost::find($c->type_id);
                        JobPostEnroll::create([
                            'user_id' => $payment->user_id,
                            'job_post_id' => $c->type_id,
                            'is_enrolled' => true,
                            'organization_id' => $recordedVideo->organization_id,
                        ]);
                    }
                    DB::delete("DELETE FROM wishlist WHERE type = ? AND type_id = ?", [$c->type, $c->type_id]);
                }                
            }
        }
    }
    public function verify_razorpay_payment(Request $request)
    {
        $payment_id = (int) $request->id;
        $payment = Payment::find($payment_id);
        if(!$payment){
            return response()->json(['status' => 'error', 'message' => 'Payment not found']);
        }

        $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_order_id = $request->razorpay_order_id;
        $razorpay_signature = $request->razorpay_signature;

        $payment->payment_id = $razorpay_payment_id;
        $payment->signature = $razorpay_signature;
        $payment->save(); 

        $secretKey = lms_setting('razorpay_api_secret');
        $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $secretKey);

        $result = [];
        if ($generated_signature === $razorpay_signature) {
            $razorpay_payment = lms_get_razorpay_payment($razorpay_payment_id);
            if($razorpay_payment && isset($razorpay_payment->status)){
                if($razorpay_payment->status == 'authorized'){
                    $payment_capture = lms_capture_razorpay_payment($razorpay_payment_id, $razorpay_payment->amount);
                    if($payment_capture && isset($payment_capture->captured) && $payment_capture->captured){
                        $this->update_razorpay_payment($payment, $razorpay_payment);
                    }
                }else if($razorpay_payment->status == 'captured'){
                    $this->update_razorpay_payment($payment, $razorpay_payment);
                }
                $result = ["status" => "success", "message" => "Payment is successful!"];
            }else{
                $result = ["status" => "error", "message" => "Payment signature verification failed!"];
            }
        } else {
            $result = ["status" => "error", "message" => "Payment verification failed!"];
        }

        return response()->json($result);
    }

    public function wishlist_save(string $type = null, int $type_id = null)
    {
        if(!$type || !$type_id || !$this->userId) return response()->json(['status' => 'error']);
        $where = [
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        $data = Wishlist::where($where)->select('id')->first();
        if(!$data){
            $obj = array_column($where, 1, 0);
            Wishlist::create($obj);
            $message = __('Added to wishlist');
            $added = true;
        }else{
            $data->delete();
            $message = __('Removed from wishlist');
            $added = false;
        }
        $result = [
            'status' => 'success',
            'message' => $message,
            'added' => $added
        ];
        return response()->json($result);
    }

    public function wishlist_remove(string $type, int $type_id)
    {
        if(!$type || !$type_id || !$this->userId) return response()->json(['status' => 'error']);
        $where = [
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        if(Wishlist::where($where)->delete()){
            return to_route('my-wishlist')->with('alert', generate_alert(__('Removed from wishlist')));
        }
        return to_route('my-wishlist')->with('alert', generate_alert(__('Selected item is not availble in wishlist'), 'danger'));
    }

    /*
    
    public function cart_save(string $type = null, int $type_id = null)
    {
        if(!$type || !$type_id || !$this->userId) return redirect()->back()->with('alert', generate_alert(__('Missing required params or sesson'), 'danger'));
        if($type == 'course'){
            $course = Course::find($type_id);
            if(!$course){
                return redirect()->back()->with('alert', generate_alert(__('Course not found'), 'danger'));
            }
        }else{
            $exam = Exam::find($type_id);
            if(!$exam){
                return redirect()->back()->with('alert', generate_alert(__('Course not found'), 'danger'));
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
            }else{
                $obj['amount'] = $exam->price;
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
            ['status', 1],
            ['user_id', $this->userId],
            ['type', $type],
            ['type_id', $type_id],
        ];
        if(Cart::where($where)->delete()){
            return redirect()->back()->with('alert', generate_alert(__('Removed from cart')));
        }
        return redirect()->back()->with('alert', generate_alert(__('Selected item is not availble in cart')));
    }
    */
}
