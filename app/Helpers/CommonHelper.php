<?php

use App\Models\OrganizationMenu;
use App\Models\OrganizationRole;
use App\Models\Qbank;
use App\Models\Setting;
if (!function_exists('p')) {
    function p($obj, $exit = true){
        echo '<pre>'; print_r($obj);
        if($exit) exit;
    }
}

if (!function_exists('lms_format_date')) {
    function lms_format_date($date, $format = 'd-M-Y h:i:s a', $default = ''){
        if (!$date || $date == "0000-00-00" || $date == "0000-00-00 00:00:00") return $default;
        return date($format, strtotime($date));
    }
}

if (!function_exists('lms_get_youtube_thumbnail')) {
    function lms_get_youtube_thumbnail($url){
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            $videoId = $matches[1];
            return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }
        return '';
    }
}

if (!function_exists('lms_get_youtube_id')) {
    function lms_get_youtube_id($url){
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/|embed\/|v\/|shorts\/))([^&?\/]+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}

if (!function_exists('lms_get_vimeo_id')) {
    function lms_get_vimeo_id($url){
        preg_match('/(?:vimeo\.com\/(?:.*\/)?)(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}

if (!function_exists('lms_get_vimeo_thumbnail')) {
    function lms_get_vimeo_thumbnail($url){
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com\/(?:.*#|.*)?videos?\/|video\/|)(\d+)/';
    if (preg_match($pattern, $url, $matches)) {
        $videoId = $matches[1];
        $apiUrl = "https://vimeo.com/api/oembed.json?url=https://vimeo.com/{$videoId}";
        $response = file_get_contents($apiUrl);
        if ($response) {
            $data = json_decode($response, true);
            return $data['thumbnail_url'];
        }
    }
        return '';
    }
}

if (!function_exists('lms_get_dailymotion_thumbnail')) {
    function lms_get_dailymotion_thumbnail($url){
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:dailymotion\.com\/video|dai\.ly)\/([a-zA-Z0-9]+)/';
        if (preg_match($pattern, $url, $matches)) {
            $videoId = $matches[1];
            return "https://www.dailymotion.com/thumbnail/video/{$videoId}";
        }
        return '';
    }
}

if (!function_exists('lms_storage')) {
    function lms_storage($path = '', $default = ''){
        if(strtolower(substr($path, 0, 4)) == 'http') return $path;
        if (!$path && $default) return $default;
        if (!$path) return asset('storage');
        return asset("storage/$path");
    }
}

if (!function_exists('lms_form_label')) {
    function lms_form_label($label, $required = true, $className = '', $attributes = ''){
        echo '<label ' . $attributes . ' class="' . $className . ' col-form-label">' . $label . ($required ? ' <span class="text-danger">*</span>' : '') . '</label>';
    }
}

if (!function_exists('lms_course_levels')) {
    function lms_course_levels(){
        return [
            'Beginner',
            'Intermediate',
            'Advanced'
        ];
    }
}

if (!function_exists('lms_course_slug')) {
    function lms_course_slug($course, $default = ''){
        if(!$course) return $default;
        return route('courses.details', ['slug' => $course->slug]);
    }
}

if (!function_exists('lms_course_pricing')) {
    function lms_course_pricing(){
        return [
            'Free',
            'Paid',
        ];
    }
}

if (!function_exists('lms_exam_pricing')) {
    function lms_exam_pricing(){
        return [
            'Free',
            'Paid',
        ];
    }
}

if (!function_exists('lms_recorded_video_slug')) {
    function lms_recorded_video_slug($recordedVideo, $action = 'details', $default = ''){
        if(!$recordedVideo) return $default;
        return route('recorded-videos.' . $action, ['slug' => $recordedVideo->slug]);
    }
}

if (!function_exists('lms_recorded_video_pricing')) {
    function lms_recorded_video_pricing(){
        return [
            'Free',
            'Paid',
        ];
    }
}

if (!function_exists('lms_job_post_slug')) {
    function lms_job_post_slug($recordedVideo, $action = 'details', $default = ''){
        if(!$recordedVideo) return $default;
        return route('job-posts.' . $action, ['slug' => $recordedVideo->slug]);
    }
}

if (!function_exists('lms_job_post_pricing')) {
    function lms_job_post_pricing(){
        return [
            'Free',
            'Paid',
        ];
    }
}

if (!function_exists('lms_custom_trim')) {
    function lms_custom_trim($string){
        $string = str_replace([
            "\xC2\xA0",     // non-breaking space (U+00A0)
            "\xE2\x80\x8B", // zero-width space (U+200B)
            "\xE2\x80\x8C", // zero-width non-joiner (U+200C)
            "\xE2\x80\x8D", // zero-width joiner (U+200D)
            "\xE2\x80\xAF", // narrow no-break space (U+202F)
            "\xE3\x80\x80", // ideographic space (U+3000)
        ], '', $string);

        return trim($string); // trim regular whitespace
    }
}

if (!function_exists('lms_array_filter')) {
    function lms_array_filter($values){
        $values = array_values(array_filter($values, function ($item) {
            return trim($item) !== '';
        }));
        return $values;
    }
}

if (!function_exists('lms_user_id')) {
    function lms_user_id(){
        return auth()->id();
    }
}

if (!function_exists('lms_organization_id')) {
    function lms_organization_id(){
        return auth()->user()->organization->id ?? 0;
    }
}

if (!function_exists('lms_is_super_admin')) {
    function lms_is_super_admin(){
        return auth()->user()->isOrganization();
    }
}

if (!function_exists('lms_organization_permissions')) {
    function lms_organization_permissions($organizationData = null){
        if($organizationData) return $organizationData->organization_permissions ?? false;
        return auth()->user()->organization->organization_permissions ?? false;
    }
}

if (!function_exists('lms_organization_menus')) {
    function lms_organization_menus($roleId){
        $role = OrganizationRole::find($roleId);
        if(!$role) return [];
        $menu_ids = $role->menu_ids ? json_decode($role->menu_ids) : [];
        $menus = OrganizationMenu::whereIn('id', $menu_ids)->where('status', 1)->pluck('routes')->toArray();
        $menus = Arr::flatten(array_map('json_decode', $menus));
        return $menus;
    }
}

if (!function_exists('lms_can_access')) {
    function lms_can_access($routeName = '', $menus = []){
        if(lms_is_organization_admin() || !lms_is_organization()) return true;
        if(!$routeName) $routeName = request()->route()->getName();
        if(!$menus) $menus = lms_organization_menus(auth()->user()->role_id);
        return in_array($routeName, $menus);
    }
}

if (!function_exists('lms_is_admin')) {
    function lms_is_admin(){
        return auth()->user()->isAdmin();
    }
}

if (!function_exists('lms_is_organization')) {
    function lms_is_organization($guard = 'web'){
        $user = auth($guard)->user();
        if (!$user) {
            return false;
        }
        return $user->isOrganization();
    }
}

if (!function_exists('lms_is_organization_admin')) {
    function lms_is_organization_admin(){
        return auth()->user()->isOrganizationAdmin();
    }
}

if (!function_exists('lms_is_student')) {
    function lms_is_student(){
        return auth()->user()->isStudent();
    }
}

if (!function_exists('lms_random_password')) {
    function lms_random_password(){
        $length = 8;
        $password = str()->random($length);
        //$password = '123456789';
        return $password;
    }
}

if (!function_exists('lms_country_list')) {
    function lms_country_list(){
        $countries = [
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BJ" => "Benin",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BR" => "Brazil",
            "BG" => "Bulgaria",
            "CA" => "Canada",
            "CL" => "Chile",
            "CN" => "China",
            "CO" => "Colombia",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "EG" => "Egypt",
            "FI" => "Finland",
            "FR" => "France",
            "DE" => "Germany",
            "GR" => "Greece",
            "HU" => "Hungary",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IT" => "Italy",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KW" => "Kuwait",
            "LB" => "Lebanon",
            "MY" => "Malaysia",
            "MX" => "Mexico",
            "MA" => "Morocco",
            "NL" => "Netherlands",
            "NZ" => "New Zealand",
            "NG" => "Nigeria",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PS" => "Palestine",
            "PH" => "Philippines",
            "PL" => "Poland",
            "PT" => "Portugal",
            "QA" => "Qatar",
            "RO" => "Romania",
            "RU" => "Russia",
            "SA" => "Saudi Arabia",
            "SG" => "Singapore",
            "ZA" => "South Africa",
            "KR" => "South Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "TW" => "Taiwan",
            "TH" => "Thailand",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "VN" => "Vietnam",
            "YE" => "Yemen"
        ];
        return $countries;
    }
}

if (!function_exists('lms_time_to_string')) {
    function lms_duration_to_string($duration){
        $parts = explode(':', $duration);
        if(count($parts) < 3) return '';

        $hours = (int) $parts[0];
        $minutes = (int) $parts[1];
        $seconds = (int) $parts[2];

        $hourText = $hours > 0 ? "$hours " . Str::plural('hour', $hours) : '';
        $minuteText = $minutes > 0 ? "$minutes " . Str::plural('minute', $minutes) : '';
        $secondText = $seconds > 0 ? "$seconds " . Str::plural('second', $seconds) : '';

        return trim("$hourText $minuteText $secondText");
    }
}

if (!function_exists('lms_duration_to_seconds')) {
    function lms_duration_to_seconds($duration){
        list($hours, $minutes, $seconds) = explode(':', $duration);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }
}

if (!function_exists('lms_exam_slug')) {
    function lms_exam_slug($exam, $action = 'details', $default = ''){
        if(!$exam) return $default;
        return route('exams.' . $action, ['slug' => $exam->slug]);
    }
}

if (!function_exists('lms_rating_numbers')) {
    function lms_rating_numbers(){
        $ratings = [
            ['rating' => 1, 'name' => 'Unsatisfactory'],
            ['rating' => 2, 'name' => 'Needs Improvement'],
            ['rating' => 3, 'name' => 'Satisfactory'],
            ['rating' => 4, 'name' => 'Commendable'],
            ['rating' => 5, 'name' => 'Outstanding'],
        ];
        return $ratings;
    }
}

if (!function_exists('lms_video_type')) {
    function lms_video_type(){
        return [
            'Upload Video',
            'YouTube / Vimeo / Online MP4 Video',
        ];
    }
}

if (!function_exists('lms_plural')) {
    function lms_plural($word, $count){
        return str()->plural($word, $count);
    }
}

if (!function_exists('lms_lesson_type_icon')) {
    function lms_lesson_type_icon($lesson_type, $document_type){
        $icon = '';
        if ($lesson_type == 'youtube'){
            $icon = '<i class="fab fa-youtube bs-tt" title="YouTube Video"></i>';
        }else if ($lesson_type == 'vimeo'){
            $icon = '<i class="fab fa-vimeo bs-tt" title="Vimeo Video"></i>';
        }else if ($lesson_type == 'image'){
            $icon = '<i class="fa-regular fa-image bs-tt" title="Image"></i>';
        }else if (in_array($lesson_type, ['mp4', 'upload'])){
            $icon = '<i class="fa-light fa-circle-play bs-tt" title="Video"></i>';
        }else if ($lesson_type == 'document'){
            if($document_type == 'text'){
                $icon = '<i class="fa-light fa-file-alt bs-tt" title="Text Document"></i>';
            }else if($document_type == 'pdf'){
                $icon = '<i class="fa-light fa-file-pdf bs-tt" title="PDF Document"></i>';
            }else{
                $icon = '<i class="fa-light fa-file bs-tt" title="Other Document"></i>';
            }
        }else if($lesson_type == 'text'){
            $icon = '<i class="fa-light fa-file-alt bs-tt" title="Text Lesson"></i>';
        }else if($lesson_type == 'iframe'){
            $icon = '<i class="fa-light fa-browser bs-tt" title="Iframe Lesson"></i>';
        }else if($lesson_type == null){
            $icon = '<i class="fa-light fa-pencil-alt bs-tt" title="Quiz"></i>';
        }
        return $icon;
    }
}

if (!function_exists('lms_lesson_type')) {
    function lms_lesson_type(){
        return [
            'youtube' => 'YouTube',
            'vimeo' => 'Vimeo',
            //'dailymotion' => 'Dailymotion',
            'mp4' => 'MP4 Video',
            'upload' => 'Upload Video',
            //'google' => 'Google Drive Video',
            'document' => 'Document',
            'image' => 'Image',
            'text' => 'Text',
            'iframe' => 'Iframe',
        ];
    }
}

if (!function_exists('lms_document_type')) {
    function lms_document_type(){
        return [
            'text' => 'Text File',
            'pdf' => 'PDF File',
            //'document' => 'Document File'
        ];
    }
}

if (!function_exists('lms_question_type')) {
    function lms_question_type(){
        return [
            'multiple' => 'Multiple Choice',
            'fill' => 'Fill in the Blanks',
            'yesno' => 'True / False'
        ];
    }
}

if (!function_exists('lms_exam_question_type')) {
    function lms_exam_question_type($value = ''){
        $data = [
            'multiple' => 'Multiple Choice',
            'fill' => 'Fill in the Blanks',
            'yesno' => 'True / False'
        ];
        if($value && isset($data[$value])){
            return $data[$value];
        }
        return $data;
    }
}

if (!function_exists('lms_course_status')) {
    function lms_course_status(){
        return [
            'Active',
            'Inactive',
            'Draft'
        ];
    }
}

if (!function_exists('lms_user_type')) {
    function lms_user_type(){
        return [
            'admin',
            'organization',
            'student',
            'tutor'
        ];
    }
}

if (!function_exists('lms_show_validation_exception_message')) {
    function lms_show_validation_exception_message($e){
        return isset(array_values($e->errors())[0][0]) ? array_values($e->errors())[0][0] : '';
    }
}

if (!function_exists('generate_alert')) {
    function generate_alert($title, $type = 'success', $from = '', $align = '', $delay = -1, $animIn = '', $animOut = ''){
        $o = ['title' => $title];
        if($type){
            $o['type'] = $type;
        }
        if($from){
            $o['from'] = $from;
        }
        if($align){
            $o['align'] = $align;
        }
        if($align){
            $o['align'] = $align;
        }
        if($animOut){
            $o['animIn'] = $animOut;
        }
        if($delay != -1){
            $o['delay'] = $delay == 0 ? false : $delay;
        }
        return json_encode($o);
    }
}

if (!function_exists('generate_link_element')) {
    function generate_link_element($args = []){
        // Set default values for parameters
        $action = $args['action'] ?? 'add';
        $route = $args['route'] ?? '';
        $label = $args['label'] ?? '';
        $class = $args['class'] ?? '';
        $title = $args['title'] ?? '';
        $route_data = $args['route_data'] ?? [];
        
        if(!$label){
            if($action == 'add'){
                $label = '<i class="ph ph-plus mb-0 mr-0"></i> ' . __('Add') ;
                $title = __('Add New');
            }else if($action == 'back'){
                $label = '<i class="ph ph-caret-left mb-0 mr-0"></i> ' . __('Back');
                $title = __('Go Back');
            }else if($action == 'edit'){
                $label = '<i class="fa fa-pencil-simple mb-0 mr-0"></i> ' . __('Edit');
                $title = __('Edit');
                $class = 'btn py-1 px-2 btn-success bs-tt';
            }
        }
        if(!$class){
            $class = 'btn py-1 px-2 btn-outline-dark bs-tt';
        }
        return [
            'label' => $label,
            'class' => $class,
            'title' => $title,
            'route' => $route ? route($route, $route_data) : '#',
        ];
    }
}

if (!function_exists('lms_category_details')) {
    function lms_category_details($course){
        if($course->thumbnail){
            $course->thumbnail = lms_storage($course->thumbnail);
        }
        return $course;
    }
}

if (!function_exists('lms_exam_category_details')) {
    function lms_exam_category_details($exam){
        if($exam->thumbnail){
            $exam->thumbnail = lms_storage($exam->thumbnail);
        }
        return $exam;
    }
}

if (!function_exists('lms_recorded_video_category_details')) {
    function lms_recorded_video_category_details($recordedVideoCategory){
        if($recordedVideoCategory->thumbnail){
            $recordedVideoCategory->thumbnail = lms_storage($recordedVideoCategory->thumbnail);
        }
        return $recordedVideoCategory;
    }
}

if (!function_exists('lms_recorded_video_details')) {
    function lms_recorded_video_details($recordedVideo){
        if($recordedVideo->thumbnail){
            $recordedVideo->thumbnail = lms_storage($recordedVideo->thumbnail);
        }
        return $recordedVideo;
    }
}

if (!function_exists('lms_job_post_category_details')) {
    function lms_job_post_category_details($recordedVideoCategory){
        if($recordedVideoCategory->thumbnail){
            $recordedVideoCategory->thumbnail = lms_storage($recordedVideoCategory->thumbnail);
        }
        return $recordedVideoCategory;
    }
}

if (!function_exists('lms_job_post_details')) {
    function lms_job_post_details($recordedVideo){
        if($recordedVideo->thumbnail){
            $recordedVideo->thumbnail = lms_storage($recordedVideo->thumbnail);
        }
        return $recordedVideo;
    }
}

if (!function_exists('lms_get_answer_text')) {
    function lms_get_answer_text($answer){
        return str_replace(config('constants.ANSWER_DELIMITER'), ', ', $answer);
    }
}

if (!function_exists('lms_student_role')) {
    function lms_student_role(){
        return ['student'];
    }
}

if (!function_exists('lms_thumb_placeholder')) {
    function lms_thumb_placeholder(){
        return asset('assets/images/placeholder.jpg');
    }
}

if (!function_exists('lms_profile_placeholder')) {
    function lms_profile_placeholder(){
        return asset('assets/images/profile-200.png');
    }
}

if (!function_exists('lms_show_price')) {
    function lms_show_price($course, $field = 'price'){
        $price = 'FREE';
        if($course->is_paid){
            $price = lms_setting('currency_symbol') . ' ' . $course->{$field};
        }
        return $price;
    }
}

if (!function_exists('lms_image')) {
    function lms_image($thumbnail, $default = ''){
        $thumb = $default ? lms_storage($default) : lms_thumb_placeholder();                            
        if($thumbnail) $thumb = lms_storage($thumbnail);
        return $thumb;
    }
}

if (!function_exists('lms_recursive_stripe_tags')) {
    function lms_recursive_stripe_tags(&$data, $excludedKeys = [])
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                lms_recursive_stripe_tags($value, $excludedKeys);
            } elseif (is_string($value) && in_array($key, $excludedKeys) === false) {
                $data[$key] = strip_tags($value);
            }
        }
    }
}

if (!function_exists('lms_course_details')) {
    function lms_course_details($course, $isArray = false){
		if($isArray){
			$course = (object)$course;
		}
        if($course->thumbnail){
            $course->thumbnail = lms_storage($course->thumbnail);
        }
        if($course->banner){
            $course->banner = lms_storage($course->banner);
        }
        if($course->uploaded_video_url){
            $course->uploaded_video_url = lms_storage($course->uploaded_video_url);
        }
        if($course->requirements){
            $requirements = json_decode($course->requirements);
            if(isset($requirements->name) && $requirements->name){
                $course->requirements = $requirements->name;
            }
        }
        if($course->outcomes){
            $outcomes = json_decode($course->outcomes);
            if(isset($outcomes->name) && $outcomes->name){
                $course->outcomes = $outcomes->name;
            }
        }
        if($course->faqs){
            $faqs = json_decode($course->faqs);
            if(isset($faqs->name) && $faqs->name){
                $result = [];
                foreach ($faqs->name as $k => $value) {
                    $result []= [
                        'question' => $value,
                        'answer' => @$faqs->value[$k]
                    ];
                }
                $course->faqs = $result;
            }
        }
		if($isArray){
			$course = (array)$course;
		}
        return $course;
    }
}

if (!function_exists('lms_exam_details')) {
    function lms_exam_details($exam){
        if($exam->thumbnail){
            $exam->thumbnail = lms_storage($exam->thumbnail);
        }
        return $exam;
    }
}

if (!function_exists('lms_setting')) {
    function lms_setting($key, $object = false){
        $setting = Setting::where([
            ['key', $key]
        ])->first();
        if($object){
            return $setting;
        }
        return $setting->value ?? '';
    }
}

if (!function_exists('lms_settings')) {
    function lms_settings($grouping = ''){
        if(!$grouping) return [];
        $settings = Setting::where([
            ['grouping', $grouping]
        ])->get()->toArray();
        return array_column($settings, 'value', 'key');
    }
}

if (!function_exists('lms_gender_types')) {
    function lms_gender_types(){
        $data = [
            'Male', 'Female', 'Others'
        ];
        return $data;
    }
}

if (!function_exists('lms_razorpay_bearer')) {
    function lms_razorpay_bearer(){
        $apiKey = lms_setting('razorpay_api_key');
        $secretKey = lms_setting('razorpay_api_secret');
        return base64_encode("$apiKey:$secretKey");
    }
}

if (!function_exists('lms_create_razorpay_order')) {
    function lms_create_razorpay_order($currency = 'INR', $amount = 0, $notes = 'exam', $receipt = ''){
        $receipt = $receipt ? $receipt : Str::uuid();
        $notes = $notes ? $notes : '';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "amount": ' . ((float) $amount * 100) . ',
                "currency": "' . $currency . '",
                "receipt": "' . $receipt . '",
                "notes": {
                    "order_type": "' . $notes . '"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . lms_razorpay_bearer()
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo "$response<br>";
        return json_decode($response);
    }
}

if (!function_exists('lms_get_razorpay_payment')) {
    function lms_get_razorpay_payment($id){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . lms_razorpay_bearer()
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}

if (!function_exists('lms_capture_razorpay_payment')) {
    function lms_capture_razorpay_payment($id, $amount, $currency = 'INR'){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.razorpay.com/v1/payments/$id/capture",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // Here 00 is not added because amount is directly coming from payment details api
            CURLOPT_POSTFIELDS =>'{
                "amount": ' . ((int) $amount) . ',
                "currency": "' . $currency . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . lms_razorpay_bearer()
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}

if (!function_exists('lms_return_json')) {
    function lms_return_json($res)
    {
        echo json_encode($res); exit;
    }
}

if (!function_exists('lms_decimal_points')) {
    function lms_decimal_points($number, $decimal = 2)
    {
        return rtrim(rtrim(number_format($number, $decimal), '0'), '.');
    }
}

if (!function_exists('lms_uuid')) {
    function lms_uuid()
    {
        return Str::uuid()->toString();
    }
}

if (!function_exists('lms_rating_to_percentage')) {
    /**
     * Convert a rating to a percentage (for 5-star rating system).
     *
     * @param float|int $rating The average rating (e.g., 3.63).
     * @param int $maxRating The maximum rating (default is 5).
     * @return float The percentage value.
     */
    function lms_rating_to_percentage($rating, $maxRating = 5, $decimal = 2)
    {
        $rating = (float) $rating;
        $rating = (($rating * 100) / $maxRating) - 2; // 2 - just for adjustments
        return lms_decimal_points($rating, $decimal);
    }
}

if (!function_exists('lms_get_qbank')) {
    function lms_get_qbank($id)
    {
        return $qbank = Qbank::where([
            ['id', $id],
            ['status', 1],
        ])->first();
    }
}

if (!function_exists('lms_calculate_grade')) {
    function lms_calculate_grade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }
}

if (!function_exists('lms_calculate_grade_bg_color')) {
    function lms_calculate_grade_bg_color($grade)
    {
        return match ($grade) {
            'A+' => '#d4edda', // light green
            'A'  => '#d1f4eb', // light teal
            'B+' => '#d6e9ff', // light blue
            'B'  => '#e0d4f7', // light indigo
            'C'  => '#ffe5d0', // light orange
            'D'  => '#fff3cd', // light yellow
            default => '#f8d7da', // light red (fail)
        };
    }
}

if (!function_exists('lms_calculate_grade_text_color')) {
    function lms_calculate_grade_text_color($grade)
    {
        return match ($grade) {
            'A+' => '#28a745', // green
            'A'  => '#20c997', // teal green
            'B+' => '#0d6efd', // blue
            'B'  => '#6610f2', // indigo
            'C'  => '#fd7e14', // orange
            'D'  => '#856404', // dark yellow/brown (for readability on light bg)
            default => '#dc3545', // red
        };
    }
}

if (!function_exists('lms_certificate_templates')) {
    function lms_certificate_templates()
    {
        return [1, 2, 3];
    }
}

if (!function_exists('lms_enum_to_array')) {
    /**
     * Convert any Enum to an array of values
     *
     * @param string $enumClass
     * @param bool $withNames include names also
     * @return array
     */
    function lms_enum_to_array(string $enumClass, bool $withNames = false): array
    {
        if (!enum_exists($enumClass)) {
            throw new InvalidArgumentException("{$enumClass} is not a valid Enum.");
        }

        if ($withNames) {
            return array_map(fn($case) => [
                'name'  => $case->name,
                'value' => $case->value,
            ], $enumClass::cases());
        }

        return array_map(fn($case) => $case->value, $enumClass::cases());
    }
}