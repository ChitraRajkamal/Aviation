<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            [
                'title' => 'Programming & Development',
                'slug' => 'programming-and-development',
                'thumbnail' => 'demo/course-categories/programming-and-development.jpg',
                'status' => 1,
                'children' => [
                    [
                        'title' => 'Web Development',
                        'slug' => 'web-development',
                        'thumbnail' => 'demo/course-categories/web-development.jpg',
                        'status' => 1,
                    ],
                    [
                        'title' => 'Mobile App Development',
                        'slug' => 'mobile-app-development',
                        'thumbnail' => 'demo/course-categories/mobile-app-development.jpg',
                        'status' => 1,
                    ],
                ],
            ],
            [
                'title' => 'Design & Multimedia',
                'slug' => 'design-and-multimedia',
                'thumbnail' => 'demo/course-categories/design-multimedia.jpg',
                'status' => 1,
                'children' => [
                    [
                        'title' => 'Graphic Design',
                        'slug' => 'graphic-design',
                        'thumbnail' => 'demo/course-categories/graphic-design.jpg',
                        'status' => 1,
                    ],
                    [
                        'title' => 'Video Editing',
                        'slug' => 'video-editing',
                        'thumbnail' => 'demo/course-categories/video-editing.jpg',
                        'status' => 1,
                    ],
                ],
            ],
        ];

        $this->insertCourseCategories($categories, 0); // Start inserting with parent_id = 0
    }

    private function insertCourseCategories(array $categories, int $parentId)
    {
        foreach ($categories as $category) {
            // Insert the category
            $newCategory = CourseCategory::create([
                'parent_id' => $parentId,
                'title' => $category['title'],
                'slug' => $category['slug'],
                'status' => $category['status'],
                'thumbnail' => $category['thumbnail'],
            ]);

            // Check for children and insert recursively
            if (isset($category['children']) && is_array($category['children'])) {
                $this->insertCourseCategories($category['children'], $newCategory->id);
            }
        }
    }
}
