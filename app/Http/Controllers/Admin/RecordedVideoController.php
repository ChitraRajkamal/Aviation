<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RecordedVideoType;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Rating;
use App\Models\RecordedVideo;
use App\Models\RecordedVideoCategory;
use App\Models\RecordedVideoEnroll;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RecordedVideoController extends BaseController
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->input('filter')){
            $recordedVideoList = RecordedVideo::query();
            if($request->search){
                $recordedVideoList = $recordedVideoList->where('title', 'LIKE', "%$request->search%");
            }
            if($request->category_id){
                $recordedVideoList = $recordedVideoList->where('recorded_video_category_id', $request->category_id);
            }
        }else{
            $recordedVideoList = RecordedVideo::query();
        }
        //$recordedVideoList = $recordedVideoList->where('organization_id', lms_organization_id());
        $recordedVideoList = $recordedVideoList->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        $categoriesHierarchy = RecordedVideoCategory::where('status', 1)->get();
        
        return view('admin.recorded-videos.index', compact('recordedVideoList', 'categoriesHierarchy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = RecordedVideoCategory::where('status', 1)->get();
        return view('admin.recorded-videos.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getRecordedVideoAddRules(0, $request->recorded_video_category_id),
            $this->getRecordedVideoAddMessages()
        );
        $data['slug'] = lms_uuid();
        $data['status'] = 1;
        $data['type'] = RecordedVideoType::Youtube->value;
        $data['is_paid'] = $data['price'] > 0;
        $data['description'] = $request->description ?? '';
        $recordedVideo = RecordedVideo::create($data);

        $metaData = $request->input('meta_data', []);
        $image = $request->file('meta_data.og_image');
        if($image){
            $metaData['og_image'] = $image->store("recorded-videos/$recordedVideo->id", 'public');
        }
        $recordedVideo->meta_data = $metaData;

        $image = $request->file('thumbnail');
        if($image){
            $recordedVideo->thumbnail = $image->store("recorded-videos/$recordedVideo->id", 'public');
        }

        if(!$recordedVideo->thumbnail) $recordedVideo->thumbnail = lms_get_youtube_thumbnail($data['link']);
        // Update Slug
        $recordedVideo->slug = str()->slug($data['title'] . ' ' . $recordedVideo->id);
        $recordedVideo->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('RecordedVideo created')));
        }
        return to_route('admin.recorded-videos.index')->with('alert', generate_alert(__('RecordedVideo created')));
    }

    /**
     * Display the specified resource.
     */
    public function show(RecordedVideo $recordedVideo)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecordedVideo $recordedVideo)
    {
        $this->getRecordedVideo($recordedVideo->id);
        $categoriesHierarchy = RecordedVideoCategory::where('status', 1)->get();
        return view('admin.recorded-videos.edit', compact('recordedVideo', 'categoriesHierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecordedVideo $recordedVideo)
    {
        $this->getRecordedVideo($recordedVideo->id);
        $data = $request->validate(
            $this->getRecordedVideoAddRules($recordedVideo->id, $recordedVideo->recorded_video_category_id),
            $this->getRecordedVideoAddMessages()
        );
        $use_youtube = (bool) $request->input('use_youtube', false);

        $check = RecordedVideo::where([
            ['id', '<>', $recordedVideo->id],
            ['slug', '=', $request->slug],
        ])->exists();
        
        if($check){
            return back()->with('alert', generate_alert(__('Duplicate slug found'), 'danger'));
        }

        $metaData = $request->input('meta_data', []);
        $image = $request->file('meta_data.og_image');
        if($image){
            $metaData['og_image'] = $image->store("recorded-videos/$recordedVideo->id", 'public');
        }
        $data['meta_data'] = $metaData;
        $data['slug'] = $request['slug'];
        $data['is_paid'] = $data['price'] > 0;
        if(!$recordedVideo->thumbnail || $use_youtube) $data['thumbnail'] = lms_get_youtube_thumbnail($data['link']);

        $file = $request->file('thumbnail');
        if($file){
            $data['thumbnail'] = $file->store("recorded-videos/$recordedVideo->id", 'public');
        }

        $recordedVideo->fill(array_merge(
            $data,
            ['description' => $request->description ?? '']
        ))->save();
        
        return back()->with('alert', generate_alert(__('Job Post updated')));
    }
    
    /**
     * Activate / Deactivate an job post.
     */
    public function activate($recordedVideoId, $status)
    {
        $recordedVideo = $this->getRecordedVideo($recordedVideoId);
        if($recordedVideo){
            $recordedVideo->status = $status == 1 ? 1 : 0;
            $recordedVideo->save();
            return redirect()->back()->with('alert', generate_alert(__('Job Post updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Job Post not available'), 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecordedVideo $recordedVideo)
    {
        $recordedVideo = $this->getRecordedVideo($recordedVideo->id);
        try {
            $recordedVideo->delete();
            return redirect()->route('admin.recorded-videos.index')->with('alert', generate_alert(__('RecordedVideo deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    public function ratings($recordedVideoId){
        $recordedVideo = $this->getRecordedVideo($recordedVideoId);
        $ratings = Rating::where([
            ['type', 'recorded-video'],
            ['type_id', $recordedVideoId],
            ['status', 1],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.recorded-videos.ratings', ['recordedVideo' => $recordedVideo, 'ratings' => $ratings]);
    }

    public function enrolments($recordedVideoId){
        $recordedVideo = $this->getRecordedVideo($recordedVideoId);
        $enrolments = RecordedVideoEnroll::where([
            ['recorded_video_id', $recordedVideoId],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.recorded-videos.enrolments', ['recordedVideo' => $recordedVideo, 'enrolments' => $enrolments]);
    }
}