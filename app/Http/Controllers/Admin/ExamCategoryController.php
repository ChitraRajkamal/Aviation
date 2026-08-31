<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Traits\ValidationsTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Storage;
use Str;

class ExamCategoryController extends Controller
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //$filteredParams = request()->except('_token');
        if($request->search){
            $categoryList = ExamCategory::query()
                ->where('title', 'LIKE', "%$request->search%")
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        }else{
            $categoryList = ExamCategory::query()
                ->orderBy('id', 'desc')
                ->paginate(lms_setting('admin_pagination_size'));
        }
        
        return view('admin.exam-categories.index', compact('categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoriesHierarchy = ExamCategory::getHierarchy();
        return view('admin.exam-categories.create', compact('categoriesHierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getExamCategoryRules(0, $request->parent_id),
            $this->getExamCategoryMessages()
        );
        
        $data['status'] = 1;
        $data['slug'] = lms_uuid();
        $data['parent_id'] = (int) $request['parent_id'];
        $category = ExamCategory::create($data);

        $image = $request->file('thumbnail');
        if($image){
            $category->thumbnail = $image->store("exam-categories/$category->id", 'public');
        }

        // Update Slug
        $category->slug = str()->slug($data['title'] . ' ' . $category->id);
        $category->save();
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('Exam category created')));
        }
        return to_route('admin.exam_categories.index')->with('alert', generate_alert(__('Exam category created')));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $examCategory = ExamCategory::find($id);
        if(!$examCategory){
            return redirect()->route('admin.exam_categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        return view('admin.exam-categories.show', compact('examCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, Request $request)
    {
        $examCategory = ExamCategory::find($id);
        if(!$examCategory){
            return redirect()->route('admin.exam_categories.index')->with('alert', generate_alert(__('Category Not Found'), 'danger'));
        }
        $categoriesHierarchy = ExamCategory::getHierarchy(0, '', $id);
        return view('admin.exam-categories.edit', compact('examCategory', 'categoriesHierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate(
            $this->getExamCategoryRules($id, $request->parent_id),
            $this->getExamCategoryMessages()
        );
        $check = ExamCategory::where([
            ['id', '<>', $id],
            ['slug', '=', $request->slug],
        ])->exists();
        
        if($check){
            return redirect()->route('admin.exam_categories.edit', ['exam_category' => $id])->with('alert', generate_alert(__('Duplicate slug found'), 'danger'));
        }
        $examCategory = ExamCategory::find($id);
        $examCategory->slug = $request['slug'];
        $examCategory->title = $request['title'];
        $examCategory->parent_id = (int) $request['parent_id'];
        $examCategory->description = $request['description'];

        $image = $request->file('thumbnail');
        if($image){
            if($examCategory->thumbnail){
                if (Storage::disk('public')->exists($examCategory->thumbnail)) {
                    Storage::disk('public')->delete($examCategory->thumbnail);
                }
            }
            $examCategory->thumbnail = $image->store("exam-categories/$examCategory->id", 'public');
        }

        $examCategory->save();
        return to_route('admin.exam_categories.index')->with('alert', generate_alert(__('Exam category updated')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $examCategory = ExamCategory::find($id);
            if($examCategory){
                if($examCategory && $examCategory->thumbnail){
                    if (Storage::disk('public')->exists($examCategory->thumbnail)) {
                        Storage::disk('public')->delete($examCategory->thumbnail);
                    }
                }

                $examCategory->delete();
                return redirect()->route('admin.exam_categories.index')->with('alert', generate_alert(__('Exam category deleted')));
            }
            return redirect()->route('admin.exam_categories.index');
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
        $category = ExamCategory::find($id);
        if($category){
            $category->status = $status == 1 ? 1 : 0;
            $category->save();
            return redirect()->back()->with('alert', generate_alert(__('Category updated')));
        }else{
            return redirect()->back()->with('alert', generate_alert(__('Category not available'), 'danger'));
        }
    }
}
