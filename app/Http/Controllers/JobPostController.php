<?php

namespace App\Http\Controllers;

use App\Models\JobPostEnroll;
use App\Models\OrganizationStudentUsage;
use App\Models\Payment;
use App\Models\Rating;
use App\Models\JobPost;
use App\Models\JobPostCategory;
use DB;
use Illuminate\Http\Request;

class JobPostController extends BaseController
{
    protected JobPost $jobPost;
    public function __construct(Request $request)
    {
        parent::__construct();
        $abort = true;
        $slug = $request->slug ?? '';
        if($slug){
            $this->jobPost = $this->getJobPostBySlug($slug, $abort);
        }        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewType = $request->cookie('lms_cl_vt') ?? 'grid';
        $filter = ['query' => '', 'category' => [], 'is_paid' => []];
        if($request->search){
            $filter['query'] = $request->input('query');
            $filter['category'] = $request->input('category');
            $filter['is_paid'] = $request->input('is_paid');
            $where = [
                ['status', 1]
            ];
            if($filter['query']){
                $where []= ['title', 'LIKE', "%$filter[query]%"];
            }
            if(!$filter['is_paid']){
                $filter['is_paid'] = [];
            }
            if(!$filter['category']){
                $filter['category'] = [];
            }
            $jobPosts = JobPost::query()->where($where )->whereHas('category', function ($query) {
                $query->where('status', 1);
            });
            if (!empty($filter['category']) && is_array($filter['category'])) {      
                $jobPosts->whereIn('job_post_category_id', $filter['category']);
            }
            if($filter['is_paid'] && is_array($filter['is_paid'])){
                $jobPosts->whereIn('is_paid', $filter['is_paid']);
            }
            $jobPosts = $jobPosts->orderBy('id', 'desc')
                ->paginate(lms_setting('course_pagination_size'))
                ->appends($request->query());
        }else{
            $jobPosts = JobPost::query()
                ->where('status', 1)
                ->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('course_pagination_size'));
        }

        $categories = JobPostCategory::withCount('job_posts')->where('status', 1)->get();
        $counts = collect([
            'is_paid' => [0, 1],
        ])->flatMap(function ($values, $key) {
            return collect($values)->mapWithKeys(function ($value) use ($key) {
                return ["{$key}_{$value}" => JobPost::where([
                    ['status', 1],
                    [$key, $value],
                ])->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })->count()];
            });
        })->toArray();
        
        return view('frontend.job-posts.index', compact('jobPosts', 'categories', 'viewType', 'filter', 'counts'));
    }

    public function details($slug)
    {
        $jobPost = $this->jobPost;
        $jobPostEnrolled = $this->isEnrolledJobPost($jobPost, false);
        if(!$jobPostEnrolled){
            if($this->autoEnrollCheck('job_posts', $jobPost->id, $jobPost->job_post_category_id)){
                JobPostEnroll::create([
                    'user_id' => $this->userId,
                    'job_post_id' => $jobPost->id,
                    'is_enrolled' => true,
                    'enrolled_date' => now(),
                    'organization_id' => $jobPost->organization_id,
                ]);
                $jobPostEnrolled = true;
                DB::table('cart') ->where('type', 'job-post') ->where('type_id', $jobPost->id) ->delete();
            }
        }

        $rated = Rating::where([
            ['user_id', $this->userId],
            ['type', 'job-post'],
            ['type_id', $jobPost->id],
            ['status', 1]
        ])->exists();
        $ratings = Rating::where([
            ['type', 'job-post'],
            ['type_id', $jobPost->id],
            ['status', 1]
        ])->orderByDesc('id')->get();
        $averageRating = $ratings->avg('rating');

        $ratingCounts = Rating::where([
            ['type', 'job-post'],
            ['type_id', $jobPost->id],
            ['status', 1]
        ])->selectRaw('rating, COUNT(*) as count')
          ->groupBy('rating')
          ->pluck('count', 'rating')
          ->toArray();
          
        $ratingCounts = array_replace(array_fill(1, 5, 0), $ratingCounts);

        $totalRatings = $ratings->count();        
        $reverseRatingCounts = array_reverse($ratingCounts, true);
        $ratingPercentages = array_map(function ($count) use ($totalRatings) {
            return $totalRatings > 0 ? round(($count / $totalRatings) * 100, 2) : 0;
        }, $reverseRatingCounts);

        return view('frontend.job-posts.details', compact('jobPost', 'jobPostEnrolled', 'rated', 'ratingPercentages', 'ratingCounts', 'ratings', 'averageRating'));
    }

    public function create_order($slug)
    {
        $jobPost = $this->jobPost;
        if(!$jobPost->is_paid){
            return back()->with('alert', generate_alert(__('This is free job post. No payment needed'), 'danger'));
        }
        $check = JobPostEnroll::where([
            ['user_id', $this->userId],
            ['job_post_id', $jobPost->id]
        ])->exists();
        
        if(!$check){
            $oldPayment = Payment::where([
                ['user_id', $this->userId],
                ['type', 'job-post'],
                ['type_id', $jobPost->id],
                ['status', 'pending']
            ])->first();
            if($oldPayment){
                return to_route('job-posts.buy', ['slug' => $slug, 'paymentId' => $oldPayment->id]);
            }
            $price = $jobPost->discount_flag ? $jobPost->discounted_price : $jobPost->price;
            $response = lms_create_razorpay_order('INR', $price, 'job-post');
            if(isset($response->error) && $response->error->description){                
                return back()->with('alert', generate_alert(__($response->error->description), 'danger'));
            }

            $payment = [
                'user_id' => $this->userId,
                'type' => 'job-post',
                'type_id' => $jobPost->id,
                'date' => now(),
                'amount' => $price,
                'order_id' => $response->id,
                'status' => 'pending',
                'source' => 'website',
                'request_data' => json_encode($response),
            ];
            $new = Payment::create($payment);
        }else{
            return back()->with('alert', generate_alert(__('You already have access to this video'), 'danger'));
        }
        return to_route('job-posts.buy', ['slug' => $slug, 'paymentId' => $new->id]);
    }

    public function buy($slug, $paymentId)
    {
        $jobPost = $this->jobPost;
        $payment = Payment::where([
            ['type', 'job-post'],
            ['id', $paymentId]
        ])->first();
        if(!$payment || $payment->status == 'captured'){
            abort(404, 'payment not found');
        }
        return view('frontend.job-posts.buy', compact('jobPost', 'payment'));
    }

    public function enroll(Request $request, $slug)
    {
        $jobPost = $this->jobPost;
        $jobPostEnrolled = $this->isEnrolledCourse($jobPost, false);
        if(!$jobPostEnrolled){
            JobPostEnroll::create([
                'user_id' => $this->userId,
                'job_post_id' => $jobPost->id,
                'is_enrolled' => true,
                'enrolled_date' => now(),
                'organization_id' => $jobPost->organization_id
            ]);
        }
        return back()->with('alert', generate_alert(__('Recorded video enrolled successfully')));   
    }
}
