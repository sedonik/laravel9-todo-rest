<?php

namespace App\Http\Controllers;

use App\Exceptions\InputException;
use App\Exceptions\TaskNotFoundException;
use App\Http\Requests\TaskAllRequest;
use App\Http\Requests\TaskPostRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * class TaskController
 */
class TaskController extends Controller
{
    /**
     * @var TaskServiceInterface $taskServiceInterface
     */
    private $taskServiceInterface;

    /**
     * @param TaskServiceInterface $taskServiceInterface
     */
    public function __construct(TaskServiceInterface $taskServiceInterface)
    {
        $this->taskServiceInterface = $taskServiceInterface;
    }

    /**
     * @param TaskAllRequest $request
     * @return TaskCollection | JsonResponse
     */
    public function index(TaskAllRequest $request): TaskCollection | JsonResponse
    {
        try {
            $this->authorize('viewAny', Task::class);
            $tasks = $this->taskServiceInterface->getAll($request->only(['filter', 'sort']));
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        }

        return new TaskCollection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskPostRequest $request
     * @return JsonResponse
     */
    public function store(TaskPostRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);
        $task = $this->taskServiceInterface->create($request->only(['title', 'parent_task_id', 'description']));

        return response()->json(new TaskResource($task), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $taskId
     * @return TaskResource|JsonResponse
     */
    public function show(int $taskId): TaskResource | JsonResponse
    {
        try {
            $this->authorize('viewAny', Task::class);
            $task = $this->taskServiceInterface->getOne($taskId);
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        }

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskPostRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(TaskPostRequest $request, Task $task): JsonResponse
    {
        try{
            $this->authorize('update', $task);
            $task = $this->taskServiceInterface->update($request->only(['title', 'description', 'status', 'priority']), $task->getOriginal('id'));
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        }

        return response()->json(new TaskResource($task), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $taskId
     * @return JsonResponse
     */
    public function destroy(int $taskId): JsonResponse
    {
        try{
            $task = $this->taskServiceInterface->getOne($taskId);
            $this->authorize('delete', $task);
            $this->taskServiceInterface->delete($taskId);
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        }

        return response()->json(null, 204);
    }
}
