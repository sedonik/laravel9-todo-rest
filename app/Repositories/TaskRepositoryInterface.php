<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

/**
 * interface TaskRepositoryInterface
 */
interface TaskRepositoryInterface
{
    /**
     * @param int $taskId
     * @param int $userId
     * @return ?Task
     */
    public function getOneByUserId(int $taskId, int $userId): ?Task;

    /**
     * @param array $request
     * @return Task
     */
    public function create(array $request): Task;

    /**
     * @param Task $task
     * @param array $request
     * @return Task
     */
    public function update(Task $task, array $request): Task;

    /**
     * @param int $taskId
     * @param int $userId
     * @return void
     */
    public function delete(Task $task): void;

    /**
     * @param array $request
     * @param int $userId
     * @return Collection
     */
    public function getAllByUserId(array $request, int $userId): Collection;
}
