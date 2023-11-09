<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Exceptions\TaskNotFoundException;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Exceptions\InputException;
use Illuminate\Support\Facades\Validator;
use App\Enums\TaskStatusEnum;
use App\Enums\TaskPriorityEnum;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * class TaskRepository
 */
class TaskRepository implements TaskInterface
{
    /**
     * @var numeric
     */
    protected $userId = 0;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;
        $this->userId = $this->session->get('user_id');
    }

    /**
     * @inheritdoc
     */
    public function create(array $request): Task
    {
        $validator = Validator::make($request, [
            'title' => "required|string|min:1|max:255",
            'description' => "required|string|min:1|max:2000",
            'parent_task_id' => "required|integer|min:1"
        ]);

        if ($validator->fails()) {
            throw new InputException($validator->errors());
        }

        return Task::create(array_merge($request, ['user_id' => $this->userId]));
    }

    /**
     * @inheritdoc
     */
    public function getOne(int $taskId): Task
    {
        $validator = Validator::make(['task_id' => $taskId], ['task_id' => "required|integer|min:1"]);
        if ($validator->fails()) {
            throw new InputException($validator->errors()->first('task_id'));
        }

        $task = Task::where('id', $taskId)
            ->where('user_id', $this->userId)
            ->first();

        if (!$task) {
            throw new TaskNotFoundException("Task: {$taskId} was not found for User: {$this->userId}");
        }

        return $task;
    }

    /**
     * @inheritdoc
     */
    public function update(array $request, int $taskId): Task
    {
        $validator = Validator::make(array_merge($request, ['task_id' => $taskId]), [
            'task_id' => "required|integer|min:1",
            'title' => "required|string|min:1|max:255",
            'description' => "required|string|min:1|max:2000",
            'status' => "string|in:" . new TaskStatusEnum(),
            'priority' => "string|in:" . new TaskPriorityEnum()
        ]);
        if ($validator->fails()) {
            throw new InputException($validator->errors());
        }

        $task = Task::where('id', '=', $taskId)
            ->where('user_id', $this->userId)
            ->first();

        if (!$task) {
            throw new TaskNotFoundException("Task: {$taskId} was not found for User: {$this->userId}");
        }

        $task['title'] = $request['title'];
        $task['description'] = $request['description'];
        $task['status'] = $request['status'];
        $task['priority'] = $request['priority'];
        $task->save();

        return $task;
    }

    /**
     * @inheritdoc
     */
    public function delete(int $taskId): void
    {
        $validator = Validator::make(['task_id' => $taskId], ['task_id' => "required|integer|min:1"]);
        if ($validator->fails()) {
            throw new InputException($validator->errors()->first('task_id'));
        }

        $task = Task::where('id', '=', $taskId)
            ->where('user_id', $this->userId)
            ->first();

        if (!$task) {
            throw new TaskNotFoundException("Task: {$taskId} was not found for User: {$this->userId}");
        }

        $task->delete();
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $request): Collection
    {
        $validator = Validator::make($request, [
            'filter.status' => "string|in:" . new TaskStatusEnum(),
            'filter.title' => "string|min:1|max:255",
            'filter.priority.*.0' => "string|in:priority",
            'filter.priority.*.1' => "string|in:>=,<=,<,>",
            'filter.priority.*.2' => "string|in:" . new TaskPriorityEnum(),
            'sort.priority' => "string|in:desc,asc",
            'sort.completed_at' => "string|in:desc,asc",
            'sort.created_at' => "string|in:desc,asc",
        ]);

        if ($validator->fails()) {
            throw new InputException($validator->errors());
        }

        $tasks = Task::where('user_id', '=', $this->userId);

        if (isset($request['sort']['priority'])) {
            $tasks->orderBy($request['sort']['priority']);
        }

        if (isset($request['sort']['completed_at'])) {
            $tasks->orderBy($request['sort']['completed_at']);
        }

        if (isset($request['sort']['created_at'])) {
            $tasks->orderBy($request['sort']['created_at']);
        }

        if (isset($request['filter']['priority'])) {
            $tasks->where($request['filter']['priority']);
        }

        if (isset($request['filter']['title'])) {
            $tasks->whereRaw("(MATCH (title) AGAINST (? IN BOOLEAN MODE))" , [$request['filter']['title']]);
        }

        if (empty($tasks->get()->toArray())) {
            throw new TaskNotFoundException("Was not found any task for User: {$this->userId}");
        }

        return $tasks->get();
    }

}
