<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

/**
 * class TaskRepository
 */
class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(array $request): Task
    {
        return Task::create($request);
    }

    /**
     * @inheritdoc
     */
    public function getOneByUserId(int $taskId, int $userId): ?Task
    {
        $task = Task::where('id', $taskId)
            ->where('user_id', $userId)
            ->first();

        return $task ?: null;
    }

    /**
     * @inheritdoc
     */
    public function updateByUserId(array $request, int $taskId, int $userId): Task
    {
        $task = Task::where('id', '=', $taskId)
            ->where('user_id', $userId)
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
    public function deleteByUserId(int $taskId, int $userId): void
    {
        $task = Task::where('id', '=', $taskId)
            ->where('user_id', $userId)
            ->first();

        $task->delete();
    }

    /**
     * @inheritdoc
     */
    public function getAllByUserId(array $request, int $userId): Collection
    {
        $tasks = Task::where('user_id', '=', $userId);

        if (isset($request['sort']['priority'])) {
            $tasks->orderBy($request['sort']['priority']);
        }

        if (isset($request['sort']['completed_at'])) {
            $tasks->orderBy($request['sort']['completed_at']);
        }

        if (isset($request['sort']['created_at'])) {
            $tasks->orderBy($request['sort']['created_at']);
        }

        if (isset($request['filter']['status'])) {
            $tasks->where(['status' => $request['filter']['status']]);
        }

        if (isset($request['filter']['priority'])) {
            $tasks->where($request['filter']['priority']);
        }

        if (isset($request['filter']['title'])) {
            $tasks->whereRaw("(MATCH (title) AGAINST (? IN BOOLEAN MODE))" , [$request['filter']['title']]);
        }

        return $tasks->get();
    }

}
