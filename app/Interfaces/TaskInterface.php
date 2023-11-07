<?php

namespace App\Interfaces;

use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * interface TaskInterface
 */
interface TaskInterface
{
    /**
     * @param int $taskId
     * @return Task
     */
    public function getOne(int $taskId): Task;

    /**
     * @param array $request
     * @return Task
     */
    public function create(array $request): Task;

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
