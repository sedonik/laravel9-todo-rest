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
     * @return Task|Exception
     */
    public function getOne(int $taskId): Task | Exception;

    /**
     * @param array $request
     * @return Task|Exception
     */
    public function create(array $request): Task | Exception;

    /**
     * @param array $request
     * @param int $taskId
     * @return Task|Exception
     */
    public function update(array $request, int $taskId): Task | Exception;

    /**
     * @param int $taskId
     * @return void
     */
    public function delete(int $taskId): void;

    /**
     * @param array $request
     * @return Collection|Exception
     */
    public function getAll(array $request): Collection | Exception;
}
