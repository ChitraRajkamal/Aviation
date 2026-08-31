<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPostCategory;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Storage;
use Str;

class JobPostCategoryController extends Controller
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->search){
            $categoryList = JobPostCategory::query()
                ->where('title', 'LIKE', "%$request->search%")
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        }else{
            $categoryList = JobPostCategory::query()
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'));
        }
        
        return view('admin.job-post-categories.index', compact('categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = JobPostCategory::where('parent_id', 0)->where('status', 1)->get();
        return view('admin.job-post-categories.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getJobPostCategoryAddRules(0, $request->parent_id),
            $this->getJobPostCategoryAddMessages()
        );
        
        $data['status'] = 1;
        $data['slug'] = lms_uuid();
        $data['parent_id'] = (int) $request['parent_id'];
        $category = JobPostCategory::create($data);

        $image = $request->file('thumbnail');
        if($image){
            $category->thumbnail = $image->store("job-post-categories/$category->id", 'public');
        }

        // Update Slug
        $category->slug = str()->slug($data['title'] . ' ' . $category->id);
        $category->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('JobPost category created')));
        }
        return to_route('admin.job-post-categories.index')->with('alert', generate_alert(__('JobPost category created')));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobPostCategory = JobPostCategory::find($id);
        if(!$jobPostCategory){
            return redirect()->route('admin.job-post-categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        return view('admin.job-post-categories.show', compact('jobPostCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, Request $request)
    {
        $jobPostCategory = JobPostCategory::find($id);
        if(!$jobPostCategory){
            return redirect()->route('admin.job-post-categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        $categoriesHierarchy = JobPostCategory::where('parent_id', 0)->where('status', 1)->whereNot('id', $id)->get();
        return view('admin.job-post-categories.edit', compact('jobPostCategory', 'categoriesHierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate(
            $this->getJobPostCategoryAddRules($id, $request->parent_id),
            $this->getJobPostCategoryAddMessages()
        );
        $check = JobPostCategory::where([
            ['id', '<>', $id],
            ['slug', '=', $request->slug],
        ])->exists();
        
        if($check){
            return back()->with('alert', generate_alert(__('Duplicate slug found'), 'danger'));
        }
        $jobPostCategory = JobPostCategory::find($id);
        $jobPostCategory->slug = $request['slug'];
        $jobPostCategory->title = $request['title'];
        $jobPostCategory->parent_id = (int) $request['parent_id'];
        $jobPostCategory->description = $request['description'];

        $image = $request->file('thumbnail');
        if($image){
            if($jobPostCategory->thumbnail){
                if (Storage::disk('public')->exists($jobPostCategory->thumbnail)) {
                    Storage::disk('public')->delete($jobPostCategory->thumbnail);
                }
            }
            $jobPostCategory->thumbnail = $image->store("job-post-categories/$jobPostCategory->id", 'public');
        }

        $jobPostCategory->save();
        return to_route('admin.job-post-categories.index')->with('alert', generate_alert(__('JobPost category updated')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $jobPostCategory = JobPostCategory::find($id);
            if($jobPostCategory){
                if($jobPostCategory && $jobPostCategory->thumbnail){
                    if (Storage::disk('public')->exists($jobPostCategory->thumbnail)) {
                        Storage::disk('public')->delete($jobPostCategory->thumbnail);
                    }
                }

                $jobPostCategory->delete();
                return redirect()->route('admin.job-post-categories.index')->with('alert', generate_alert(__('JobPost category deleted')));
            }
            return redirect()->route('admin.job-post-categories.index');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }        
    }

    /**
     * Activate / Deactivate an category.
     */
    public function activate($id, $status)
    {
        $category = JobPostCategory::find($id);
        if($category){
            $category->status = $status == 1 ? 1 : 0;
            $category->save();
            return redirect()->back()->with('alert', generate_alert(__('Category updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Category not available'), 'danger'));
        }
    }
}
