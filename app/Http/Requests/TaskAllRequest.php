<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

/**
 * class TaskAllRequest
 */
class TaskAllRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return  [
            "filter.status" => "string|in:" . TaskStatusEnum::getImplodedList(),
            "filter.title" => "string|min:1|max:255",
            "filter.priority.*.0" => "string|in:priority",
            "filter.priority.*.1" => "string|in:>=,<=,<,>",
            "filter.priority.*.2" => "string|in:" . TaskPriorityEnum::getImplodedList(),
            "sort.priority" => "string|in:desc,asc",
            "sort.completed_at" => "string|in:desc,asc",
            "sort.created_at" => "string|in:desc,asc"
        ];
    }
}
