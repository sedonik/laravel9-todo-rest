<?php

namespace App\Http\Controllers;

use App\Exceptions\InputException;
use App\Exceptions\TaskNotFoundException;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * class TaskController
 */
class TaskController extends Controller
{
    /**
     * @var TaskInterface
     */
    private $taskInterface;

    /**
     * @param TaskInterface $taskInterface
     */
    public function __construct(TaskInterface $taskInterface)
    {
        $this->taskInterface = $taskInterface;
    }

    /**
     * @param Request $request
     * @return TaskCollection | JsonResponse
     */
    public function index(Request $request): TaskCollection | JsonResponse
    {
        try {
            $this->authorize('viewAny', Task::class);
            $tasks = $this->taskInterface->getAll($request->only(['filter', 'sort']));
        } catch (InputException $inputException) {
            return response()->json(json_decode($inputException->getMessage()), 400);
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

        return new TaskCollection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('create', Task::class);
            $task = $this->taskInterface->create($request->only(['title', 'parent_task_id', 'description']));
        } catch (InputException $inputException) {
            return response()->json(json_decode($inputException->getMessage()), 400);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

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
            $task = $this->taskInterface->getOne($taskId);
        } catch (InputException $inputException) {
            return response()->json($inputException->getMessage(), 400);
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        try{
            $this->authorize('update', $task);
            $task = $this->taskInterface->update($request->only(['title', 'description', 'status', 'priority']), $task->getOriginal('id'));
        } catch (InputException $inputException) {
            return response()->json($inputException->getMessage(), 400);
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
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
            $task = $this->taskInterface->getOne($taskId);
            $this->authorize('delete', $task);
            $this->taskInterface->delete($taskId);
        } catch (InputException $inputException) {
            return response()->json($inputException->getMessage(), 400);
        } catch (TaskNotFoundException $notFoundException) {
            return response()->json($notFoundException->getMessage(), 404);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

        return response()->json(null, 204);
    }
}
