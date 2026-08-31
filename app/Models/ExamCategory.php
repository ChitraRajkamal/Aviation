<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{
    use HasFactory;

    protected $table = 'exam_categories';
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class, 'exam_category_id');
    }

    public function exam_count()
    {
        return $this->hasMany(Exam::class, 'exam_category_id')->where('organization_id', lms_organization_id())->count();
    }

    public function children()
    {
        return $this->hasMany(ExamCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ExamCategory::class, 'parent_id');
    }

    public static function getHierarchy($parentId = 0, $prefix = '', $ignoreId = 0, $populateExam = false)
    {
        $categories = self::where('parent_id', $parentId)->get();
        $result = [];
        
        foreach ($categories as $category) {
            if($ignoreId != $category->id) {
                $obj = (object) [
                    'id' => $category->id,
                    'parent_id' => $category->parent_id,
                    'thumbnail' => $category->thumbnail,
                    'title' => $prefix . $category->title,
                ];
                if($populateExam) {
                    $obj->title = $category->title;
                    $exams = Exam::where([
                        ['status', 1],
                        ['exam_category_id', $category->id]
                    ])->get();
                    foreach ($exams as $key => &$d) {
                        $d = lms_exam_details($d);
                    }
                    $obj->exams = $exams;
                }
                $result[] = $obj;
            }

            // Recursively get children categories
            $result = array_merge($result, self::getHierarchy($category->id, $prefix . $category->title . ' -> ', $ignoreId, $populateExam));
        }

        return $result;
    }

    public static function getCategoryWithExamCount()
    {
        $parentCategories = ExamCategory::where('parent_id', 0)->where('status', 1)->get();
        return $parentCategories->map(function ($category) {
            return (object)[
                'id' => $category->id,
                'title' => $category->title,
                'thumbnail' => $category->thumbnail,
                'exams_count' => count($category->allExams())
            ];
        });
    }

    public function allExams()
    {
        // Start with exams in the current category
        $exams = $this->exams;

        // Recursively include exams from child categories
        foreach ($this->children as $child) {
            $exams = $exams->merge($child->allExams());
        }

        return $exams;
    }

    public static function getCategoriesWithExams()
    {
        // Execute the query to get the categories and exams count
        $categories = DB::select('
            WITH RECURSIVE category_tree AS (
                SELECT 
                    id,
                    parent_id,
                    title,
                    id AS root_id
                FROM exam_categories
                WHERE status = 1
                UNION ALL
                SELECT 
                    c.id,
                    c.parent_id,
                    c.title,
                    ct.root_id
                FROM exam_categories c
                INNER JOIN category_tree ct ON c.parent_id = ct.id
                WHERE c.status = 1
            )
            SELECT 
                exam_categories.id AS id,
                exam_categories.parent_id,
                exam_categories.title,
                SUM(IFNULL(exams_count, 0)) AS total_exams
            FROM category_tree
            LEFT JOIN (
                SELECT exam_category_id, COUNT(*) AS exams_count
                FROM exams
                WHERE status = 1
                GROUP BY exam_category_id
            ) exam_counts ON category_tree.id = exam_counts.exam_category_id
            LEFT JOIN exam_categories ON category_tree.root_id = exam_categories.id
            WHERE exam_categories.status = 1
            GROUP BY exam_categories.id, exam_categories.parent_id, exam_categories.title
            ORDER BY exam_categories.parent_id, exam_categories.title;
        ');

        // Build the hierarchical structure from the flat result
        $categoryTree = self::buildCategoryTree($categories);
        
        return $categoryTree;
    }

    public static function buildCategoryTree($categories)
    {
        $categoryMap = [];
        $categoryTree = [];

        // Map categories by ID
        foreach ($categories as $category) {
            $categoryMap[$category->id] = (object)[
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'title' => $category->title,
                'total_exams' => $category->total_exams,
                'children' => []
            ];
        }

        // Build the hierarchical structure
        foreach ($categoryMap as $category) {
            if ($category->parent_id) {
                // If the category has a parent, add it to the parent's children
                if(isset($categoryMap[$category->parent_id])) $categoryMap[$category->parent_id]->children[] = $category;
            } else {
                // If the category has no parent, it is a root category
                $categoryTree[] = $category;
            }
        }

        return $categoryTree;
    }

    public static function getAllCategoryIds($categoryIds)
    {
        // Initialize with the given categories
        $allCategoryIds = collect($categoryIds);

        // Recursive query to get all child categories
        $childCategoryIds = ExamCategory::whereIn('parent_id', $categoryIds)->pluck('id');

        if ($childCategoryIds->isNotEmpty()) {
            $allCategoryIds = $allCategoryIds->merge(self::getAllCategoryIds($childCategoryIds->toArray()));
        }

        return $allCategoryIds->unique(); // Remove duplicates
    }
}
