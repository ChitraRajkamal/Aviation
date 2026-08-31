<?php

namespace Database\Seeders;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'app_name' => [
                'value' => 'Example inc',
                'description' => 'The name of the application.',
                'is_required' => true,
            ],
            'phone' => [
                'value' => '+911234567890',
                'description' => 'Phone number with country code. Ex: +919876543210 (+9 - India)',
                'is_required' => true,
            ],
            'phone_1' => [
                'value' => '',
                'description' => 'Alternative Phone number with country code. Ex: +919876543210 (+9 - India)',
                'is_required' => false,
            ],
            'email' => [
                'value' => 'hello@example.com',
                'description' => 'Email ID',
                'is_required' => true,
            ],
            'email_1' => [
                'value' => '',
                'description' => 'Alternative Email ID',
                'is_required' => false,
            ],
            'address' => [
                'value' => '12, Main Road, City, ST - 12345, India',
                'description' => 'Full Address',
                'is_required' => true,
            ],
            'admin_pagination_size' => [
                'value' => 10,
                'description' => 'Admin listing pagination size',
                'is_required' => true,
            ],
            'course_pagination_size' => [
                'value' => 6,
                'description' => 'Course pagination size',
                'is_required' => true,
            ],
            'exam_pagination_size' => [
                'value' => 6,
                'description' => 'Exam pagination size',
                'is_required' => true,
            ],
            'frontend_pagination_size' => [
                'value' => 10,
                'description' => 'Pagination size of other listing of frontend',
                'is_required' => true,
            ],
            'razorpay_api_key' => [
                'value' => 'rzp_test_6Aevd3ijwBkDow',
                'description' => 'API key for Razorpay payment gateway.',
                'is_required' => true,
                'grouping' => 'razorpay',
            ],
            'razorpay_api_secret' => [
                'value' => '0OZzby1ZE5yiHa9XxuxY1KTI',
                'description' => 'API secret for Razorpay payment gateway.',
                'is_required' => true,
                'grouping' => 'razorpay',
            ],
            'google_login_client_id' => [
                'value' => '332078041093-4j2dekelne2s3tr1gqttgtr3to73nidj.apps.googleusercontent.com',
                'description' => 'Client ID for Google Login.',
                'is_required' => true,
                'grouping' => 'google-login',
            ],
            'facebook_login_app_id' => [
                'value' => '698041026221944',
                'description' => 'App ID for Facebook Login.',
                'is_required' => true,
                'grouping' => 'facebook-login',
            ],
            'facebook_graph_api_version' => [
                'value' => 'v22.0',
                'description' => 'Facebook Grahp API Version.',
                'is_required' => true,
                'grouping' => 'facebook-login',
            ],
            'currency' => [
                'value' => 'INR',
                'description' => 'Payment currency.',
                'is_required' => true,
            ],
            'currency_symbol' => [
                'value' => '₹',
                'description' => 'Symbol for the default currency.',
                'is_required' => true,
            ],
            'user_min_age' => [
                'value' => 12,
                'description' => 'Minimum age required to register.',
                'is_required' => true,
            ],
            'contact_email' => [
                'value' => 'hussainmh39@gmail.com',
                'description' => 'Support contact email address.',
                'is_required' => true,
            ],
            'social_facebook' => [
                'value' => 'https://www.facebook.com/',
                'description' => 'URL to the Facebook page.',
                'grouping' => 'social-media',
            ],
            'social_twitter' => [
                'value' => 'https://twitter.com/',
                'description' => 'URL to the Twitter profile.',
                'grouping' => 'social-media',
            ],
            'social_linkedin' => [
                'value' => 'https://www.linkedin.com/',
                'description' => 'URL to the LinkedIn profile.',
                'grouping' => 'social-media',
            ],
            'social_instagram' => [
                'value' => 'https://www.instagram.com/',
                'description' => 'URL to the Instagram profile.',
                'grouping' => 'social-media',
            ],
            'social_youtube' => [
                'value' => 'https://www.youtube.com/',
                'description' => 'URL to the YouTube channel.',
                'grouping' => 'social-media',
            ],
            /*'home_sliders' => [
                'value' => 'https://www.youtube.com/',
                'description' => 'URL to the YouTube channel.',
                'is_custom' => true,
                'grouping' => 'home-sliders',
            ], */
            'certificate_title' => [
                'value' => 'Certificate of Completion',
                'description' => 'Certificate of Completion.',
                'grouping' => 'certificate',
            ],
            'certificate_logo' => [
                'value' => '',
                'description' => 'Certificate Logo',
                'grouping' => 'certificate',
                'type' => 'file:image',
            ],
            'certificate_signature_image' => [
                'value' => '',
                'description' => 'Signature image for certificate.',
                'grouping' => 'certificate',
                'type' => 'file:image',
            ],
            'course_certificate_template' => [
                'value' => 1,
                'picklist' => lms_certificate_templates(),
                'description' => 'Course Certificate Template',
                'grouping' => 'certificate',
                'type' => 'dropdown',
            ],
            'exam_certificate_template' => [
                'value' => 1,
                'picklist' => lms_certificate_templates(),
                'description' => 'Exam Certificate Template',
                'grouping' => 'certificate',
                'type' => 'dropdown',
            ],
        ];
    
        $records = collect($data)->map(fn($item, $key) => [
            'key' => $key,
            'value' => $item['value'],
            'picklist' => $item['picklist'],
            'is_required' => $item['is_required'] ?? false,
            'is_custom' => $item['is_custom'] ?? false,
            'grouping' => $item['grouping'] ?? 'general',
            'type' => $item['type'] ?? 'text',
            'description' => $item['description'],
            'created_at' => now(),
            'updated_at' => now(),
            'created_by_id' => 1,
        ])->toArray();
    
        Setting::insert($records);
    }
}
