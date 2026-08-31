<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Str;

class AjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function update_razorpay_payment($payment, $razorpay_payment)
    {
        $payment->status = 'captured';
        $payment->response_data = json_encode($razorpay_payment);
        $payment->save(); 
        if($payment->type == 'subscription'){
            $subscription = Subscription::find($payment->type_id);
            $subscription->status = SubscriptionStatus::Approved->value;
            $subscription->expiry = Carbon::now()->addYear();
            $subscription->save();
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
}