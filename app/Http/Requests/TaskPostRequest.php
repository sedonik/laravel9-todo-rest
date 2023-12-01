<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

/**
 * class TaskPostRequest
 */
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
            "title" => "required|string|min:1|max:255",
            "description" => "required|string|min:1|max:2000",
            "parent_task_id" => "integer|min:1",
            "status" => "string|in:" . implode(',', array_column(TaskStatusEnum::cases(), 'value')),
            "priority" => "integer|in:" . implode(',', array_column(TaskPriorityEnum::cases(), 'value'))
        ];
    }
}
