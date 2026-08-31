<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\JobPost;
use App\Models\JobPostCategory;
use App\Models\JobPostEnroll;
use App\Models\Rating;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class JobPostController extends BaseController
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->input('filter')){
            $jobPostList = JobPost::query();
            if($request->search){
                $jobPostList = $jobPostList->where('title', 'LIKE', "%$request->search%");
            }
            if($request->category_id){
                $jobPostList = $jobPostList->where('job_post_category_id', $request->category_id);
            }
        }else{
            $jobPostList = JobPost::query();
        }
        //$jobPostList = $jobPostList->where('organization_id', lms_organization_id());
        $jobPostList = $jobPostList->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        $categoriesHierarchy = JobPostCategory::where('status', 1)->get();
        
        return view('admin.job-posts.index', compact('jobPostList', 'categoriesHierarchy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = JobPostCategory::where('status', 1)->get();
        return view('admin.job-posts.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getJobPostAddRules(0, $request->job_post_category_id),
            $this->getJobPostAddMessages()
        );
        $data['slug'] = lms_uuid();
        $data['status'] = 1;
        $data['is_paid'] = $data['price'] > 0;
        $data['description'] = $request->description ?? '';
        $jobPost = JobPost::create($data);

        $metaData = $request->input('meta_data', []);
        $image = $request->file('meta_data.og_image');
        if($image){
            $metaData['og_image'] = $image->store("job-posts/$jobPost->id", 'public');
        }
        $jobPost->meta_data = $metaData;

        $image = $request->file('thumbnail');
        if($image){
            $jobPost->thumbnail = $image->store("job-posts/$jobPost->id", 'public');
        }

        // Update Slug
        $jobPost->slug = str()->slug($data['title'] . ' ' . $jobPost->id);
        $jobPost->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('JobPost created')));
        }
        return to_route('admin.job-posts.index')->with('alert', generate_alert(__('JobPost created')));
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPost $jobPost)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobPost $jobPost)
    {
        $this->getJobPost($jobPost->id);
        $categoriesHierarchy = JobPostCategory::where('status', 1)->get();
        return view('admin.job-posts.edit', compact('jobPost', 'categoriesHierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobPost $jobPost)
    {
        $this->getJobPost($jobPost->id);
        $data = $request->validate(
            $this->getJobPostAddRules($jobPost->id, $jobPost->job_post_category_id),
            $this->getJobPostAddMessages()
        );

        $check = JobPost::where([
            ['id', '<>', $jobPost->id],
            ['slug', '=', $request->slug],
        ])->exists();
        
        if($check){
            return back()->with('alert', generate_alert(__('Duplicate slug found'), 'danger'));
        }

        $metaData = $request->input('meta_data', []);
        $image = $request->file('meta_data.og_image');
        if($image){
            $metaData['og_image'] = $image->store("job-posts/$jobPost->id", 'public');
        }
        $data['meta_data'] = $metaData;
        $data['slug'] = $request['slug'];
        $data['is_paid'] = $data['price'] > 0;

        $file = $request->file('thumbnail');
        if($file){
            $data['thumbnail'] = $file->store("job-posts/$jobPost->id", 'public');
        }

        $jobPost->fill(array_merge(
            $data,
            ['description' => $request->description ?? '']
        ))->save();
        
        return back()->with('alert', generate_alert(__('Job Post updated')));
    }
    
    /**
     * Activate / Deactivate an job post.
     */
    public function activate($jobPostId, $status)
    {
        $jobPost = $this->getJobPost($jobPostId);
        if($jobPost){
            $jobPost->status = $status == 1 ? 1 : 0;
            $jobPost->save();
            return redirect()->back()->with('alert', generate_alert(__('Job Post updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Job Post not available'), 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPost $jobPost)
    {
        $jobPost = $this->getJobPost($jobPost->id);
        try {
            $jobPost->delete();
            return redirect()->route('admin.job-posts.index')->with('alert', generate_alert(__('JobPost deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    public function ratings($jobPostId){
        $jobPost = $this->getJobPost($jobPostId);
        $ratings = Rating::where([
            ['type', 'job-post'],
            ['type_id', $jobPostId],
            ['status', 1],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.job-posts.ratings', ['jobPost' => $jobPost, 'ratings' => $ratings]);
    }

    public function enrolments($jobPostId){
        $jobPost = $this->getJobPost($jobPostId);
        $enrolments = JobPostEnroll::where([
            ['job_post_id', $jobPostId],
        ])->when(lms_is_organization(), function ($q) {
            $q->whereHas('user', function ($query) {
                $query->where('organization_id', lms_organization_id());
            });
        })->orderByDesc('id')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.job-posts.enrolments', ['jobPost' => $jobPost, 'enrolments' => $enrolments]);
    }
}