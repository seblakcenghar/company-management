<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'logo' => ['required', 'image', 'mimes:png', 'max:2048', 'dimensions:min_width=100,min_height=100'],
            'website' => ['required', 'url', 'max:255'],
        ];
    }
}
