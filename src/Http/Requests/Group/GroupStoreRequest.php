<?php

namespace TomatoPHP\TomatoCRM\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_tomato_translations_ar' => ['required', 'string', 'max:255'],
            'name_tomato_translations_en' => ['required', 'string', 'max:255'],
            'description_tomato_translations_en' => ['required', 'string', 'max:255'],
            'description_tomato_translations_en' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255']
        ];
    }
}
