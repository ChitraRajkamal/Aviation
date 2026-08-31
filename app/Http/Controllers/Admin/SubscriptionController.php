<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubscriptionPaymentType;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Admin\BaseController;
use App\Models\CourseCategory;
use App\Models\ExamCategory;
use App\Models\JobPostCategory;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\RecordedVideoCategory;
use App\Models\Subscription;
use App\Traits\ValidationsTrait;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SubscriptionController extends BaseController
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->input('filter')){
            $subscriptionList = Subscription::query();
            if($request->organization_id){
                $subscriptionList = $subscriptionList->where('organization_id', $request->organization_id);
            }
        }else{
            $subscriptionList = Subscription::query();
        }
        if(lms_is_organization_admin()) $subscriptionList = $subscriptionList->where('organization_id', lms_organization_id());
        $subscriptionList = $subscriptionList->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        
        $organizations = Organization::where('status', 1)->get();
        if(lms_is_organization_admin()){
            return view('admin.subscriptions.index-organization', compact('subscriptionList', 'organizations'));
        }else{
            return view('admin.subscriptions.index', compact('subscriptionList', 'organizations'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::where('status', 1)->get();
        return view('admin.subscriptions.create', compact('organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getSubscriptionAddRules(),
            $this->getSubscriptionAddMessages()
        );
        $data['status'] = SubscriptionStatus::Unpaid->value;
        Subscription::create($data);
        
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('Subscription created')));
        }
        return to_route('admin.subscriptions.index')->with('alert', generate_alert(__('Subscription created')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.details', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        $this->getSubscription($subscription->id);
        $organizations = Organization::where('status', 1)->get();
        return view('admin.subscriptions.edit', compact('subscription', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->getSubscription($subscription->id);
        $data = $request->validate(
            $this->getSubscriptionAddRules(),
            $this->getSubscriptionAddMessages()
        );

        $subscription->fill(array_merge(
            $data,
        ))->save();
        
        return back()->with('alert', generate_alert(__('Job Post updated')));
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription = $this->getSubscription($subscription->id);
        if($subscription->status == SubscriptionStatus::Approved->value){
            return back()->with('alert', generate_alert(__('Subscription cannot be deleted as it is already approved'), 'danger'));
        }
        try {
            $subscription->delete();
            return redirect()->route('admin.subscriptions.index')->with('alert', generate_alert(__('Subscription deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    public function pay($id)
    {
        $subscription = $this->getSubscription($id);
        if($subscription->status == SubscriptionStatus::Paid->value || $subscription->status == SubscriptionStatus::Approved->value){
            return to_route('admin.subscriptions.index');
        }
        return view('admin.subscriptions.pay', compact('subscription'));
    }

    public function save_payment(Request $request, $id)
    {
        $subscription = $this->getSubscription($id);
        if($subscription->status == SubscriptionStatus::Paid->value || $subscription->status == SubscriptionStatus::Approved->value){
            return to_route('admin.subscriptions.index');
        }
        $rules = [
            'type' => ['required'],
        ];
        if($request->type != SubscriptionPaymentType::Razorpay->value){
            $rules['remarks'] = ['required'];
        }
        $data = $request->validate($rules, [
            'remarks.required' => 'Details field is required'
        ]);
        if($request->type == SubscriptionPaymentType::Razorpay->value){
            $response = lms_create_razorpay_order('INR', $subscription->price, 'subscription');
            if($response == null){
                return back()->with('alert', generate_alert(__('Error contacting razorpay'), 'danger'));
            }
            if(isset($response->error) && optional($response->error)->description){
                return back()->with('alert', generate_alert(__($response->error->description), 'danger'));
            }

            $payment = [
                'user_id' => lms_user_id(),
                'type' => 'subscription',
                'type_id' => $subscription->id,
                'date' => now(),
                'amount' => $subscription->price,
                'order_id' => $response->id,
                'status' => 'pending',
                'source' => 'website',
                'request_data' => json_encode($response),
            ];
            $payment = Payment::create($payment);
            $data['payment_id'] = $payment->id;
        }else{
            $data['status'] = SubscriptionStatus::Paid->value;
        }

        $subscription->fill(array_merge(
            $data,
        ))->save();

        if($request->type == SubscriptionPaymentType::Razorpay->value){
            return to_route('admin.subscriptions.pay-razorpay', ['id' => $subscription->id])->with('alert', generate_alert(__('Payment updated and waiting for approval')));
        }
        
        return to_route('admin.subscriptions.index')->with('alert', generate_alert(__('Payment updated and waiting for approval')));
    }

    public function pay_razorpay($id)
    {
        $subscription = $this->getSubscription($id);
        if($subscription->status == SubscriptionStatus::Paid->value || $subscription->status == SubscriptionStatus::Approved->value || !$subscription->payment_id){
            return to_route('admin.subscriptions.index');
        }
        $payment = Payment::find($subscription->payment_id);
        return view('admin.subscriptions.razorpay', compact('subscription', 'payment'));
    }

    public function approve($id)
    {
        $subscription = $this->getSubscription($id);
        if($subscription){
            $subscription->status = SubscriptionStatus::Approved->value;
            $subscription->expiry = Carbon::now()->addYear();
            $subscription->save();
            return back()->with('alert', generate_alert(__('Subscription approved')));
        }else{
            return back()->with('alert', generate_alert(__('Subscription not available'), 'danger'));
        }
    }

    public function setup($organizationId)
    {
        $organization = Organization::findOrFail($organizationId);
        $subscriptions = Subscription::where([
            ['organization_id', $organizationId],
            ['status', SubscriptionStatus::Approved->value]
        ])->get();
        if (!$subscriptions) {
            return back();
        }
        /*$courseCategories = CourseCategory::where([
            ['status', 1],
            ['parent_id', 0]
        ])->get();*/        
        $courseCategories = CourseCategory::getCategoryWithCourses();
        $examCategories = ExamCategory::where([
            ['status', 1],
            ['parent_id', 0]
        ])->get();
        $videoCategories = RecordedVideoCategory::where([
            ['status', 1],
            ['parent_id', 0]
        ])->get();
        $jobCategories = JobPostCategory::where([
            ['status', 1],
            ['parent_id', 0]
        ])->get();
        return view('admin.subscriptions.setup', 
                compact('organization', 'subscriptions', 'courseCategories', 'examCategories', 'jobCategories', 'videoCategories'));
    }

    public function save_setup(Request $request, $organizationId)
    {
        $data = $request->validate([
            'no_of_students' => 'required|integer|min:1',
            'organization_permissions' => 'required|array',
        ],[
            'organization_permissions.required' => 'Please select at least one item from course or exams or jobs or videos'
        ]);
        $subscriptions = Subscription::where([
            ['organization_id', $organizationId],
            ['status', SubscriptionStatus::Approved->value]
        ])->exists();
        if (!$subscriptions) {
            return to_route('admin.subscriptions.index');
        }
        $organization = Organization::findOrFail($organizationId);
        $organization->update([
            'no_of_students' => $data['no_of_students'],
            'organization_permissions' => $data['organization_permissions'] ?? [],
        ]);
        return back()->with('alert', generate_alert(__('Permissions updated')));
    }
}