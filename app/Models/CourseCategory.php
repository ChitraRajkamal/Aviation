<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $table = 'course_categories';
    
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public function parent()
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }

    public function categoryPath($separator = ' -> ')
    {
        $path = [$this->title];

        $parent = $this->parent;
        while ($parent) {
            array_unshift($path, $parent->title);
            $parent = $parent->parent;
        }

        return implode($separator, $path); // Join the path with " -> "
    }

    public static function getCategoryPath($categoryId, $separator = ' -> ')
    {
        $category = CourseCategory::find($categoryId);
        return $category ? $category->categoryPath($separator) : 'Category not found.';
    }

    public function children()
    {
        return $this->hasMany(CourseCategory::class, 'parent_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }

    public function coursesForOrgPermission($orgId)
    {
        return $this->hasMany(Course::class, 'course_category_id')
            ->where('organization_id', $orgId);
    }

    // 2. Recursively fetch all courses for an org
    public function allCoursesForOrgPermission($orgId)
    {
        // Start with current category courses (collection)
        $courses = $this->coursesForOrgPermission($orgId)->get();

        // Recursively merge child category courses
        foreach ($this->children as $child) {
            $courses = $courses->merge($child->allCoursesForOrgPermission($orgId));
        }

        return $courses;
    }

    // 3. Static function to get categories with courses
    public static function getCategoryWithCoursesForOrgPermission($orgId)
    {
        $parentCategories = self::where('parent_id', 0)->get();

        return $parentCategories->map(function ($category) use ($orgId) {
            return (object)[
                'id' => $category->id,
                'title' => $category->title,
                'courses' => $category->allCoursesForOrgPermission($orgId)->map(function ($course) {
                    return (object)[
                        'id' => $course->id,
                        'title' => $course->title,
                    ];
                }),
            ];
        });
    }

    public function allCourses()
    {
        // Start with courses in the current category
        $courses = $this->courses;

        // Recursively include courses from child categories
        foreach ($this->children as $child) {
            $courses = $courses->merge($child->allCourses());
        }

        return $courses;
    }

    public static function getCategoryWithCourses()
    {
        $parentCategories = CourseCategory::where('parent_id', 0)->get();
        return $parentCategories->map(function ($category) {
            return (object)[
                'id' => $category->id,
                'title' => $category->title,
                'thumbnail' => $category->thumbnail,
                'courses' => $category->allCourses()->map(function ($course) {
                    return (object)[
                        'id' => $course->id,
                        'slug' => $course->slug,
                        'title' => $course->title,
                        'is_paid' => $course->is_paid,
                        'discount_flag' => $course->discount_flag,
                        'discounted_price' => $course->discounted_price,
                        'price' => $course->price,
                        'thumbnail' => $course->thumbnail,
                        'course_category_id' => $course->course_category_id,
                        'ratings' => $course->rating_average(),
                        'lesson_count' => $course->lesson_count(),
                        'enroll_count' => $course->enroll_count(),
                    ];
                }),
            ];
        });
    }

    public static function getAllCategoryIds($categoryIds)
    {
        // Initialize with the given categories
        $allCategoryIds = collect($categoryIds);

        // Recursive query to get all child categories
        $childCategoryIds = CourseCategory::whereIn('parent_id', $categoryIds)->pluck('id');

        if ($childCategoryIds->isNotEmpty()) {
            $allCategoryIds = $allCategoryIds->merge(self::getAllCategoryIds($childCategoryIds->toArray()));
        }

        return $allCategoryIds->unique(); // Remove duplicates
    }

    public static function getCategoryWithCourseCount()
    {
        $parentCategories = CourseCategory::where('parent_id', 0)->where('status', 1)->get();
        return $parentCategories->map(function ($category) {
            return (object)[
                'id' => $category->id,
                'title' => $category->title,
                'thumbnail' => $category->thumbnail,
                'courses_count' => count($category->allCourses())
            ];
        });
    }

    public function courses1($limit = 10, $orderBy = ['id', 'desc'])
    {
        return $this->hasMany(Course::class, 'course_category_id')
            ->orderBy($orderBy[0], $orderBy[1])
            ->take($limit);
    }

    /*public function courses()
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }*/

    public static function getHierarchy($parentId = 0, $prefix = '', $ignoreId = 0, $populateCourse = false)
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
                if($populateCourse) {
                    $obj->title = $category->title;
                    $courses = Course::where([
                        ['status', 'Active'],
                        ['course_category_id', $category->id]
                    ])->get();
                    foreach ($courses as $key => &$d) {
                        $d = lms_course_details($d);
                    }
                    $obj->courses = $courses;
                }
                $result[] = $obj;
            }

            // Recursively get children categories
            $result = array_merge($result, self::getHierarchy($category->id, $prefix . $category->title . ' -> ', $ignoreId, $populateCourse));
        }

        return $result;
    }

    public static function getCategoryHierachyWithCounts()
    {
        $categories = DB::select("
            WITH RECURSIVE category_tree AS (
                SELECT 
                    id,
                    parent_id,
                    title,
                    id AS root_id
                FROM course_categories
                WHERE status = 1
                UNION ALL
                SELECT 
                    c.id,
                    c.parent_id,
                    c.title,
                    ct.root_id
                FROM course_categories c
                INNER JOIN category_tree ct ON c.parent_id = ct.id
            )
            SELECT 
                root_id AS id,
                course_categories.title,
                SUM(IFNULL(courses_count, 0)) AS total_courses
            FROM category_tree
            LEFT JOIN (
                SELECT course_category_id, COUNT(*) AS courses_count
                FROM courses
                GROUP BY course_category_id
            ) course_counts ON category_tree.id = course_counts.course_category_id
            LEFT JOIN course_categories ON category_tree.root_id = course_categories.id
            GROUP BY root_id, course_categories.title
        ");
        return $categories;
    }

    public static function getCategoriesWithCourses()
    {
        // Execute the query to get the categories and courses count
        $categories = DB::select('
            WITH RECURSIVE category_tree AS (
                SELECT 
                    id,
                    parent_id,
                    title,
                    id AS root_id
                FROM course_categories
                WHERE status = 1
                UNION ALL
                SELECT 
                    c.id,
                    c.parent_id,
                    c.title,
                    ct.root_id
                FROM course_categories c
                INNER JOIN category_tree ct ON c.parent_id = ct.id
                WHERE c.status = 1
            )
            SELECT 
                course_categories.id AS id,
                course_categories.parent_id,
                course_categories.title,
                SUM(IFNULL(courses_count, 0)) AS total_courses
            FROM category_tree
            LEFT JOIN (
                SELECT course_category_id, COUNT(*) AS courses_count
                FROM courses
                WHERE status = \'Active\'
                GROUP BY course_category_id
            ) course_counts ON category_tree.id = course_counts.course_category_id
            LEFT JOIN course_categories ON category_tree.root_id = course_categories.id
            WHERE course_categories.status = 1
            GROUP BY course_categories.id, course_categories.parent_id, course_categories.title
            ORDER BY course_categories.parent_id, course_categories.title;
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
                'total_courses' => $category->total_courses,
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
}
