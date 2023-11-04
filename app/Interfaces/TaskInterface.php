<?php

namespace App\Interfaces;

/**
 * interface TaskInterface
 */
interface TaskInterface
{
    /**
     * @param $taskId
     * @return task
     */
    public function getOne(int $taskId);

    /**
     * @param $request
     * @return task
     */
    public function create($request);

    /**
     * @param $request
     * @param $taskId
     * @return task
     */
    public function update($request, $taskId);

    /**
     * @param $taskId
     * @return void
     */
    public function delete($taskId);

    /**
     * @param $request
     * @return tasks
     */
    public function getAll($request);
}
