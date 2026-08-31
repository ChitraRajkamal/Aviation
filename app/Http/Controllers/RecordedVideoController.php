<?php

namespace App\Http\Controllers;

use App\Models\RecordedVideoEnroll;
use App\Models\Payment;
use App\Models\Rating;
use App\Models\RecordedVideo;
use App\Models\RecordedVideoCategory;
use DB;
use Illuminate\Http\Request;

class RecordedVideoController extends BaseController
{
    protected RecordedVideo $recordedVideo;
    public function __construct(Request $request)
    {
        parent::__construct();
        $abort = true;
        $slug = $request->slug ?? '';
        if($slug){
            $this->recordedVideo = $this->getRecordedVideoBySlug($slug, $abort);
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
            $recordedVideos = RecordedVideo::query()->where($where )->whereHas('category', function ($query) {
                $query->where('status', 1);
            });
            if (!empty($filter['category']) && is_array($filter['category'])) {      
                $recordedVideos->whereIn('recorded_video_category_id', $filter['category']);
            }
            if($filter['is_paid'] && is_array($filter['is_paid'])){
                $recordedVideos->whereIn('is_paid', $filter['is_paid']);
            }
            $recordedVideos = $recordedVideos->orderBy('id', 'desc')
                ->paginate(lms_setting('course_pagination_size'))
                ->appends($request->query());
        }else{
            $recordedVideos = RecordedVideo::query()
                ->where('status', 1)
                ->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('course_pagination_size'));
        }

        $categories = RecordedVideoCategory::withCount('recorded_videos')->where('status', 1)->get();
        $counts = collect([
            'is_paid' => [0, 1],
        ])->flatMap(function ($values, $key) {
            return collect($values)->mapWithKeys(function ($value) use ($key) {
                return ["{$key}_{$value}" => RecordedVideo::where([
                    ['status', 1],
                    [$key, $value],
                ])->whereHas('category', function ($query) {
                    $query->where('status', 1);
                })->count()];
            });
        })->toArray();
        
        return view('frontend.recorded-videos.index', compact('recordedVideos', 'categories', 'viewType', 'filter', 'counts'));
    }

    public function details($slug)
    {
        $recordedVideo = $this->recordedVideo;
        $recordedVideoEnrolled = $this->isEnrolledRecordedVideo($recordedVideo, false);
        if(!$recordedVideoEnrolled){
            if($this->autoEnrollCheck('recorded_videos', $recordedVideo->id, $recordedVideo->recorded_video_category_id)){
                RecordedVideoEnroll::create([
                    'user_id' => $this->userId,
                    'recorded_video_id' => $recordedVideo->id,
                    'is_enrolled' => true,
                    'enrolled_date' => now(),
                    'organization_id' => $recordedVideo->organization_id,
                ]);
                $recordedVideoEnrolled = true;
                DB::table('cart') ->where('type', 'recorded-video') ->where('type_id', $recordedVideo->id) ->delete();
            }
        }

        $rated = Rating::where([
            ['user_id', $this->userId],
            ['type', 'recorded-video'],
            ['type_id', $recordedVideo->id],
            ['status', 1]
        ])->exists();
        $ratings = Rating::where([
            ['type', 'recorded-video'],
            ['type_id', $recordedVideo->id],
            ['status', 1]
        ])->orderByDesc('id')->get();
        $averageRating = $ratings->avg('rating');

        $ratingCounts = Rating::where([
            ['type', 'recorded-video'],
            ['type_id', $recordedVideo->id],
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

        return view('frontend.recorded-videos.details', compact('recordedVideo', 'recordedVideoEnrolled', 'rated', 'ratingPercentages', 'ratingCounts', 'ratings', 'averageRating'));
    }

    public function create_order($slug)
    {
        $recordedVideo = $this->recordedVideo;
        if(!$recordedVideo->is_paid){
            return back()->with('alert', generate_alert(__('This is free recorded video. No payment needed'), 'danger'));
        }
        $check = RecordedVideoEnroll::where([
            ['user_id', $this->userId],
            ['recorded_video_id', $recordedVideo->id]
        ])->exists();
        
        if(!$check){
            $oldPayment = Payment::where([
                ['user_id', $this->userId],
                ['type', 'recorded-video'],
                ['type_id', $recordedVideo->id],
                ['status', 'pending']
            ])->first();
            if($oldPayment){
                return to_route('recorded-videos.buy', ['slug' => $slug, 'paymentId' => $oldPayment->id]);
            }
            $price = $recordedVideo->discount_flag ? $recordedVideo->discounted_price : $recordedVideo->price;
            $response = lms_create_razorpay_order('INR', $price, 'recorded-video');
            if(isset($response->error) && $response->error->description){                
                return back()->with('alert', generate_alert(__($response->error->description), 'danger'));
            }

            $payment = [
                'user_id' => $this->userId,
                'type' => 'recorded-video',
                'type_id' => $recordedVideo->id,
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
        return to_route('recorded-videos.buy', ['slug' => $slug, 'paymentId' => $new->id]);
    }

    public function buy($slug, $paymentId)
    {
        $recordedVideo = $this->recordedVideo;
        $payment = Payment::where([
            ['type', 'recorded-video'],
            ['id', $paymentId]
        ])->first();
        if(!$payment || $payment->status == 'captured'){
            abort(404, 'payment not found');
        }
        return view('frontend.recorded-videos.buy', compact('recordedVideo', 'payment'));
    }

    public function enroll(Request $request, $slug)
    {
        $recordedVideo = $this->recordedVideo;
        $recordedVideoEnrolled = $this->isEnrolledCourse($recordedVideo, false);
        if(!$recordedVideoEnrolled){
            $enroll = RecordedVideoEnroll::create([
                'user_id' => $this->userId,
                'recorded_video_id' => $recordedVideo->id,
                'is_enrolled' => true,
                'enrolled_date' => now(),
                'organization_id' => $recordedVideo->organization_id,
            ]);         
        }
        return back()->with('alert', generate_alert(__('Recorded video enrolled successfully')));   
    }
}
