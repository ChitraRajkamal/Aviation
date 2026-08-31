<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        if($search){
            $paymentList = Payment::query()
                ->where(function ($query) use ($search) {
                    $query->where('payment_id', 'LIKE', "%$search%")
                        ->orWhere('order_id', 'LIKE', "%$search%");
                })
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        }else{
            $paymentList = Payment::query()
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'));
        }
        
        return view('admin.payments.index', compact('paymentList'));
    }
}
