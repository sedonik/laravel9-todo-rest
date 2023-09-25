<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * class TaskController
 */
class TaskController extends Controller
{
    /**
     * @var numeric
     */
    public $userId = 0;

    /**
     * @var Session
     */
    protected $session;

    /**
     * Authenticate constructor.
     *
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;

        $this->userId = $this->session->get('user_id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $this->userId;

        $tasks = Task::findAll($request->only([
            'filter',
            'sort'
        ]), $userId);

        $tasksCollection = new TaskCollection($tasks);


        return response()->json($tasksCollection, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->only([
           'status', 'priority', 'title', 'description', 'created_at', 'completed_at'
        ]);

        $params['user_id'] = $this->userId;

        $task = Task::create($params);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show($taskId)
    {
        $userId = $this->userId;

        $task = Task::getTask($taskId, $userId);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $params = $request->only([
            'status', 'priority', 'title', 'description', 'created_at', 'completed_at'
        ]);

        $params['user_id'] = $this->userId;

        $task->update($params);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($taskId)
    {
        $userId = $this->userId;

        Task::deleteTask($taskId, $userId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
