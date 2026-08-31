<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
{
    public function settings()
    {
        $settingList = Setting::where('is_custom', false)->where('grouping', 'general')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.settings.general', compact('settingList'));
    }

    public function settings_certificate()
    {
        $settingList = Setting::where('is_custom', false)->where('grouping', 'certificate')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.settings.certificate', compact('settingList'));
    }

    public function settings_social_media()
    {
        $settingList = Setting::where('is_custom', false)->where('grouping', 'social-media')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.settings.social-media', compact('settingList'));
    }

    public function settings_razorpay()
    {
        $settingList = Setting::where('is_custom', false)->where('grouping', 'razorpay')->paginate(lms_setting('admin_pagination_size'));
        return view('admin.settings.razorpay', compact('settingList'));
    }

    public function settings_social_login()
    {
        $settingList = Setting::where('is_custom', false)->whereIn('grouping', ['google-login', 'facebook-login'])->paginate(lms_setting('admin_pagination_size'));
        return view('admin.settings.social-login', compact('settingList'));
    }

    public function edit_setting($id)
    {
        $setting = Setting::find($id);
        if(!$setting){
            abort(404, 'Setting not found');
        }
        return view('admin.settings.edit-setting', compact('setting'));
    }

    public function update_setting(Request $request, $id)
    {
        $setting = Setting::find($id);
        if(!$setting){
            abort(404, 'Setting not found');
        }
        $is_required = $setting->is_required;
        $field_validation = [];
        $field_messages = [];
        if($setting->type == 'file:image'){
            $field_validation []= [($is_required && !$setting->value ? 'required' : 'nullable'),'image','mimes:jpeg,png,jpg,gif','max:1024'];
            $field_messages['value.image'] = 'The file must be a valid image';
            $field_messages['value.mimes'] = 'Only jpeg, png, jpg, and gif formats are allowed';
            $field_messages['value.max'] = 'The image size should not exceed 1MB';
            $field_messages['value.required'] = 'Upload a valid image';
        }
        if($setting->type != 'file:image' && $is_required){
            $field_validation []= 'required';
            $field_messages['value.required'] = 'Value is required';
        }
        if($field_validation){
            
            $validator = Validator::make($request->only('value'), [
                'value' => $field_validation,
            ], $field_messages);
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $data = $validator->getData();
        }else{
            $data = $request->only('value');
        }
        if($setting->type == 'file:image'){
            $data['value'] = $setting->value;
            $file = $request->file('value');
            if($file){
                $data['value'] = $file->store("settings/$setting->id", 'public');
            }
        }
        
        Setting::where('id', $id)->update([
            'value' => $data['value'] ?? '',
            'updated_by_id' => lms_user_id(),
        ]);
        return back()->with('alert', generate_alert(__('Setting saved')));
    }

    public function edit_organization_setting()
    {
        $setting = OrganizationSetting::where('organization_id', lms_organization_id())->first();
        if(!$setting){
            $setting = OrganizationSetting::create([
                'organization_id' => lms_organization_id(),
                'certificate_logo' => '',
                'certificate_signature' => '',
                'course_certificate_title' => 'Certificate of Completion',
                'course_certificate_template' => 1,
                'exam_certificate_title' => 'Certificate of Completion',
                'exam_certificate_template' => 1,
            ]);
        }
        return view('admin.settings.organization-setting', compact('setting'));
    }

    public function update_organization_setting(Request $request)
    {
        $setting = OrganizationSetting::where('organization_id', lms_organization_id())->firstOrFail();
        $rules = [
            'course_certificate_title' => ['required'],
            'course_certificate_template' => ['required'],
            'exam_certificate_title' => ['required'],
            'exam_certificate_template' => ['required'],
        ];
        $data = $request->validate($rules);

        $image = $request->file('certificate_logo');
        if($image){
            $data['certificate_logo'] = $image->store("organization/$setting->organization_id", 'public');
        }
        $image = $request->file('certificate_signature');
        if($image){
            $data['certificate_signature'] = $image->store("organization/$setting->organization_id", 'public');
        }

        $setting->fill($data);
        $setting->save();
        return back()->with('alert', generate_alert(__('Setting saved')));
    }
}
