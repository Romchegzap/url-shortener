<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreUrlRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'originalUrl' => 'required|url'
        ];
    }

    public function messages(): array
    {
        return [
            'originalUrl.required' => 'Url is required',
            'originalUrl.url' => 'Url format is incorrect'
        ];
    }
}
