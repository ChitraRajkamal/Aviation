<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    private $image_upload_size = 1024;
    private $image_upload_size_message = '1MB';
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female'],
            'dob' => ['required', 'date', 'before_or_equal:' . Carbon::now()->subYears(10)->format('Y-m-d')],
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:' . $this->image_upload_size,
            /*'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],*/
        ];
    }

    public function messages(): array
    {
        return [
            'image' => [
                'image' => 'The file must be a valid image.',
                'mimes' => 'Only jpeg, png, jpg, and gif formats are allowed.',
                'max' => "The image size should not exceed {$this->image_upload_size_message}.",
            ],
        ];
    }
}
