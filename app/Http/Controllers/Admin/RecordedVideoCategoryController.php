<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecordedVideoCategory;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Storage;
use Str;

class RecordedVideoCategoryController extends Controller
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->search){
            $categoryList = RecordedVideoCategory::query()
                ->where('title', 'LIKE', "%$request->search%")
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        }else{
            $categoryList = RecordedVideoCategory::query()
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'));
        }
        
        return view('admin.recorded-video-categories.index', compact('categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = RecordedVideoCategory::where('parent_id', 0)->where('status', 1)->get();
        return view('admin.recorded-video-categories.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getRecordedVideoCategoryAddRules(0, $request->parent_id),
            $this->getRecordedVideoCategoryAddMessages()
        );
        
        $data['status'] = 1;
        $data['slug'] = lms_uuid();
        $data['parent_id'] = (int) $request['parent_id'];
        $category = RecordedVideoCategory::create($data);

        $image = $request->file('thumbnail');
        if($image){
            $category->thumbnail = $image->store("recorded-video-categories/$category->id", 'public');
        }

        // Update Slug
        $category->slug = str()->slug($data['title'] . ' ' . $category->id);
        $category->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('RecordedVideo category created')));
        }
        return to_route('admin.recorded-video-categories.index')->with('alert', generate_alert(__('RecordedVideo category created')));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobPostCategory = RecordedVideoCategory::find($id);
        if(!$jobPostCategory){
            return redirect()->route('admin.recorded-video-categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        return view('admin.recorded-video-categories.show', compact('jobPostCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, Request $request)
    {
        $jobPostCategory = RecordedVideoCategory::find($id);
        if(!$jobPostCategory){
            return redirect()->route('admin.recorded-video-categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        $categoriesHierarchy = RecordedVideoCategory::where('parent_id', 0)->where('status', 1)->whereNot('id', $id)->get();
        return view('admin.recorded-video-categories.edit', compact('jobPostCategory', 'categoriesHierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate(
            $this->getRecordedVideoCategoryAddRules($id, $request->parent_id),
            $this->getRecordedVideoCategoryAddMessages()
        );
        $check = RecordedVideoCategory::where([
            ['id', '<>', $id],
            ['slug', '=', $request->slug],
        ])->exists();
        
        if($check){
            return back()->with('alert', generate_alert(__('Duplicate slug found'), 'danger'));
        }
        $jobPostCategory = RecordedVideoCategory::find($id);
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
            $jobPostCategory->thumbnail = $image->store("recorded-video-categories/$jobPostCategory->id", 'public');
        }

        $jobPostCategory->save();
        return to_route('admin.recorded-video-categories.index')->with('alert', generate_alert(__('RecordedVideo category updated')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $jobPostCategory = RecordedVideoCategory::find($id);
            if($jobPostCategory){
                if($jobPostCategory && $jobPostCategory->thumbnail){
                    if (Storage::disk('public')->exists($jobPostCategory->thumbnail)) {
                        Storage::disk('public')->delete($jobPostCategory->thumbnail);
                    }
                }

                $jobPostCategory->delete();
                return redirect()->route('admin.recorded-video-categories.index')->with('alert', generate_alert(__('RecordedVideo category deleted')));
            }
            return redirect()->route('admin.recorded-video-categories.index');
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
        $category = RecordedVideoCategory::find($id);
        if($category){
            $category->status = $status == 1 ? 1 : 0;
            $category->save();
            return redirect()->back()->with('alert', generate_alert(__('Category updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Category not available'), 'danger'));
        }
    }
}
