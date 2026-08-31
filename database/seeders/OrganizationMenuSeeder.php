<?php

namespace Database\Seeders;

use App\Models\OrganizationMenu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrganizationMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sortBy = 1;
        $defaultValues = [
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Step 1: Define the menu structure with parent-child relationships
        $menus = [
            [
                'name' => 'Dashboard',
                'routes' => ['admin.dashboard'],
                'children' => []
            ],
            [
                'name' => 'Courses',
                'children' => [
                    ['name' => 'Listing', 'routes' => ['admin.courses.index']],
                    ['name' => 'Create', 'routes' => ['admin.courses.create', 'admin.courses.store']],
                    ['name' => 'Edit', 'routes' => ['admin.courses.edit', 'admin.courses.custom-edit', 'admin.courses.update', 'admin.courses.sections.sort', 'admin.courses.lessons.sort']],
                    ['name' => 'Activate / Deactivate', 'routes' => ['admin.courses.activate']],
                    ['name' => 'Delete', 'routes' => ['admin.courses.destroy']],
                    ['name' => 'Ratings', 'routes' => ['admin.courses.ratings']],
                    ['name' => 'Enrolments', 'routes' => ['admin.courses.enrolments']],

                    ['name' => 'Section Create', 'routes' => ['admin.courses.sections.create']],
                    ['name' => 'Section Edit', 'routes' => ['admin.courses.sections.virtual-edit']],
                    ['name' => 'Section Delete', 'routes' => ['admin.courses.sections.delete']],

                    ['name' => 'Lesson Create', 'routes' => ['admin.courses.lessons.create', 'admin.courses.lessons.save']],
                    ['name' => 'Lesson Edit', 'routes' => ['admin.courses.lessons.edit', 'admin.courses.lessons.save']],
                    ['name' => 'Lesson Delete', 'routes' => ['admin.courses.lessons.delete']],

                    ['name' => 'Quiz Create', 'routes' => ['admin.courses.quizs.create', 'admin.courses.quizs.save']],
                    ['name' => 'Quiz Edit', 'routes' => ['admin.courses.quizs.edit', 'admin.courses.quizs.save']],
                    ['name' => 'Quiz Delete', 'routes' => ['admin.courses.quizs.delete']],

                    ['name' => 'Quiz Question Listing', 'routes' => ['admin.courses.questions']],
                    ['name' => 'Quiz Question Create', 'routes' => ['admin.courses.questions.create', 'admin.courses.questions.save']],
                    ['name' => 'Quiz Question Delete', 'routes' => ['admin.courses.questions.delete']],
                    ['name' => 'Quiz Results', 'routes' => ['admin.courses.quiz-results', 'admin.courses.quiz-result-details']],
                ]
            ],
            [
                'name' => 'Exams',
                'children' => [
                    ['name' => 'Listing', 'routes' => ['admin.exams.index']],
                    ['name' => 'Create', 'routes' => ['admin.exams.create', 'admin.exams.store']],
                    ['name' => 'Edit', 'routes' => ['admin.exams.edit', 'admin.exams.update']],
                    ['name' => 'Activate / Deactivate', 'routes' => ['admin.exams.activate']],
                    ['name' => 'Delete', 'routes' => ['admin.exams.destroy']],
                    ['name' => 'Ratings', 'routes' => ['admin.exams.ratings']],
                    ['name' => 'Results', 'routes' => ['admin.exams.results']],
                ]
            ],
            [
                'name' => 'Question Banks',
                'children' => [
                    ['name' => 'Listing', 'routes' => ['admin.qbank.index']],
                    ['name' => 'Create', 'routes' => ['admin.qbank.create', 'admin.qbank.store']],
                    ['name' => 'Edit', 'routes' => ['admin.qbank.edit', 'admin.qbank.update']],
                    ['name' => 'Activate / Deactivate', 'routes' => ['admin.qbank.activate']],
                    ['name' => 'Delete', 'routes' => ['admin.qbank.destroy']],

                    ['name' => 'Question Listing', 'routes' => ['admin.qbank.questions']],
                    ['name' => 'Question Import', 'routes' => ['admin.qbank.import-questions', 'admin.qbank.upload_questions', 'admin.qbank.review_questions', 'admin.qbank.disable_bulk_questions', 'admin.qbank.save_bulk_questions']],
                    ['name' => 'Question Create', 'routes' => ['admin.qbank.questions.create', 'admin.qbank.questions.save']],
                    ['name' => 'Question Edit', 'routes' => ['admin.qbank.questions.edit', 'admin.qbank.questions.save']],
                    ['name' => 'Question Activate / Deactivate', 'routes' => ['admin.qbank.questions.activate']],
                    ['name' => 'Question Delete', 'routes' => ['admin.qbank.questions.delete']],
                ]
            ],
            [
                'name' => 'Students',
                'children' => [
                    ['name' => 'Listing', 'routes' => ['admin.users.student']],                    
                    ['name' => 'Create', 'routes' => ['admin.users.student.create','admin.users.student.save']],
                    ['name' => 'Edit', 'routes' => ['admin.users.student.edit', 'admin.users.student.save']],
                    ['name' => 'Activate / Deactivate', 'routes' => ['admin.users.student.activate']],
                    ['name' => 'Import', 'routes' => ['admin.users.student.import', 'admin.users.student.upload', 'admin.users.student.review', 'admin.users.student.save_bulk']],
                ]
            ],
            [
                'name' => 'Staffs',
                'children' => [
                    ['name' => 'Listing', 'routes' => ['admin.users.organization']],
                    ['name' => 'Create', 'routes' => ['admin.users.organization.staff.create', 'admin.users.organization.staff.save']],
                    ['name' => 'Edit', 'routes' => ['admin.users.organization.staff.edit', 'admin.users.organization.staff.save']],
                    ['name' => 'Activate / Deactivate', 'routes' => ['admin.users.organization.activate']],
                ]
            ],
            [
                'name' => 'Roles',
                'children' => [
                    ['name' => 'Listing', 'routes' => ['admin.roles.organization']],
                    ['name' => 'Create', 'routes' => ['admin.roles.organization.create', 'admin.roles.organization.save']],
                    ['name' => 'Edit', 'routes' => ['admin.roles.organization.edit', 'admin.roles.organization.save']],
                    ['name' => 'Delete', 'routes' => ['admin.roles.organization.delete']],
                ]
            ],
        ];

        // Step 2: Insert parent menus and store their IDs
        $parentIds = [];

        foreach ($menus as $key => $menu) {
            $name = $menu['name'];
            $parent = OrganizationMenu::create(array_merge($defaultValues, [
                'name' => $name,
                'routes' => isset($menu['routes']) ? json_encode($menu['routes']) : '[]',
                'is_heading' => true,
                'parent_id' => 0,
                'sort_by' => $sortBy++,
            ]));

            $parentIds[$name] = $parent->id;

            // Step 3: Insert child menus linked to parent_id
            foreach ($menu['children'] as $child) {
                OrganizationMenu::create(array_merge($defaultValues, [
                    'name' => $child['name'],
                    'routes' => json_encode($child['routes']) ?? '[]',
                    'is_heading' => false,
                    'parent_id' => $parent->id,
                    'sort_by' => $sortBy++,
                ]));
            }
        }
    }
}
