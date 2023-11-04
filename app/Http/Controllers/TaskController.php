<?php

namespace App\Http\Controllers;

use App\Exceptions\InputException;
use App\Exceptions\TaskNotFoundException;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
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
     */
    public function store(Request $request)
    {
        try {
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
     */
    public function show($taskId)
    {
        try {
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
     */
    public function update(Request $request, Task $task)
    {
        try{
            $task = $this->taskInterface->update($request->only(['title', 'description']), $task->getOriginal('id'));
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
     */
    public function destroy($taskId)
    {
        try{
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
