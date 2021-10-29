<?php

namespace Rvsitebuilder\Laravelsitemap\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'setting_data' => 'required',
        ];
    }

    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function messages(): array
    {
        return [
            'setting_data.required' => 'The "setting_data" field is required.',
        ];
    }
}
