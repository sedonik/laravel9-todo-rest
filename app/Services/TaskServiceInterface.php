<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskServiceInterface
{
    /**
     * @param array $request
     * @return Task
     */
    public function create(array $request): Task;

    /**
     * @param int $taskId
     * @return Task
     * @throws TaskNotFoundException
     */
    public function getOne(int $taskId): Task;

    /**
     * @param array $request
     * @param int $taskId
     * @return Task
     */
    public function update(array $request, int $taskId): Task;

    /**
     * @param int $taskId
     * @return void
     */
    public function delete(int $taskId): void;

    /**
     * @param array $request
     * @return Collection
     */
    public function getAll(array $request): Collection;
}

