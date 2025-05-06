<?php

namespace App\Http\Requests\Zoho;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deal_name' => ['required', 'string'],
            'deal_stage' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'account_website' => ['required', 'url'],
            'account_phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
        ];
    }
}
