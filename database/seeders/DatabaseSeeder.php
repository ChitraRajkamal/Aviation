<?php
namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'role' => 'admin',
                'email' => 'super.admin@gmail.com',
                'password' => Hash::make('123456789'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'first_name' => 'Super Alt',
                'last_name' => 'Admin',
                'role' => 'admin',
                'email' => 'lms.admin@gmail.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        
        DB::table('languages')->insert([
            'name' => 'English',
            'direction' => 'ltr',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->call([
            CourseCategorySeeder::class,
            OrganizationMenuSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
