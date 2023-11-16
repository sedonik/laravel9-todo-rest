<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => "required|string|min:1|max:255",
            'description' => "required|string|min:1|max:2000",
            'parent_task_id' => "required|integer|min:1"
        ];
    }
}
