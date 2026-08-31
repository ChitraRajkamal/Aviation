<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Storage;
use Str;

class CourseCategoryController extends Controller
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$filteredParams = request()->except('_token');
        if($request->search){
            $categoryList = CourseCategory::query()
                ->where('title', 'LIKE', "%$request->search%")
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        }else{
            $categoryList = CourseCategory::query()
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'));
        }
        
        return view('admin.course-categories.index', compact('categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = CourseCategory::getHierarchy();
        return view('admin.course-categories.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getCourseCategoryRules(0, $request->parent_id),
            $this->getCourseCategoryMessages()
        );
        
        $data['status'] = 1;
        $data['slug'] = lms_uuid();
        $data['parent_id'] = (int) $request['parent_id'];
        $category = CourseCategory::create($data);

        $image = $request->file('thumbnail');
        if($image){
            $category->thumbnail = $image->store("course-categories/$category->id", 'public');
        }

        // Update Slug
        $category->slug = str()->slug($data['title'] . ' ' . $category->id);
        $category->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('Course category created')));
        }
        return to_route('admin.course_categories.index')->with('alert', generate_alert(__('Course category created')));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $courseCategory = CourseCategory::find($id);
        if(!$courseCategory){
            return redirect()->route('admin.course_categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        return view('admin.course-categories.show', compact('courseCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, Request $request)
    {
        $courseCategory = CourseCategory::find($id);
        if(!$courseCategory){
            return redirect()->route('admin.course_categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        $categoriesHierarchy = CourseCategory::getHierarchy(0, '', $id);
        return view('admin.course-categories.edit', compact('courseCategory', 'categoriesHierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseCategory $courseCategory, $id)
    {
        $data = $request->validate(
            $this->getCourseCategoryRules($id, $request->parent_id),
            $this->getCourseCategoryMessages()
        );
        $check = CourseCategory::where([
            ['id', '<>', $id],
            ['slug', '=', $request->slug],
        ])->exists();
        
        if($check){
            return redirect()->route('admin.course_categories.edit', ['category' => $id])->with('alert', generate_alert(__('Duplicate slug found'), 'danger'));
        }
        $courseCategory = CourseCategory::find($id);
        $courseCategory->slug = $request['slug'];
        $courseCategory->title = $request['title'];
        $courseCategory->parent_id = (int) $request['parent_id'];
        $courseCategory->description = $request['description'];

        $image = $request->file('thumbnail');
        if($image){
            if($courseCategory->thumbnail){
                if (Storage::disk('public')->exists($courseCategory->thumbnail)) {
                    Storage::disk('public')->delete($courseCategory->thumbnail);
                }
            }
            $courseCategory->thumbnail = $image->store("course-categories/$courseCategory->id", 'public');
        }

        $courseCategory->save();
        return to_route('admin.course_categories.index')->with('alert', generate_alert(__('Course category updated')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $courseCategory = CourseCategory::find($id);
            if($courseCategory){
                if($courseCategory && $courseCategory->thumbnail){
                    if (Storage::disk('public')->exists($courseCategory->thumbnail)) {
                        Storage::disk('public')->delete($courseCategory->thumbnail);
                    }
                }

                $courseCategory->delete();
                return redirect()->route('admin.course_categories.index')->with('alert', generate_alert(__('Course category deleted')));
            }
            return redirect()->route('admin.course_categories.index');
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
        $category = CourseCategory::find($id);
        if($category){
            $category->status = $status == 1 ? 1 : 0;
            $category->save();
            return redirect()->back()->with('alert', generate_alert(__('Category updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Category not available'), 'danger'));
        }
    }
}
