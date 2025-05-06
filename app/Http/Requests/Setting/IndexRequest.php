<?php

namespace App\Http\Requests\Setting;

use App\Enums\Setting\Key;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
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
            'keys' => ['required', 'array'],
            'keys.*' => ['required', 'string', Rule::in(Key::values())],
        ];
    }
}
