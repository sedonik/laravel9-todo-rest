<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repositories\TaskRepositoryInterface;
use App\Enums\TaskStatusEnum;

class TaskService implements TaskServiceInterface
{
    /**
     * @var numeric
     */
    protected $userId = 0;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @param Session $session
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        Session $session,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->session = $session;

        $this->userId = $this->session->get('user_id');
    }

    /**
     * @inheritdoc
     */
    public function create(array $request): Task
    {
        return $this->taskRepository->create(['user_id' => $this->userId, ...$request]);
    }

    /**
     * @inheritdoc
     */
    public function getOne(int $taskId): Task
    {
        $task = $this->taskRepository->getOneByUserId($taskId, $this->userId);

        if (!$task) {
            throw new TaskNotFoundException("Task: {$taskId} was not found for User: {$this->userId}");
        }

        return $task;
    }

    /**
     * @inheritdoc
     */
    public function update(array $request, int $taskId): Task
    {
        $task = $this->taskRepository->getOneByUserId($taskId, $this->userId);

        if (!$task) {
            throw new TaskNotFoundException("Task: {$taskId} was not found for User: {$this->userId}");
        }

        return $this->taskRepository->update($task, $request);
    }

    /**
     * @inheritdoc
     */
    public function delete(int $taskId): void
    {
        $task = $this->taskRepository->getOneByUserId($taskId, $this->userId);

        if (!$task) {
            throw new TaskNotFoundException("Task: {$taskId} was not found for User: {$this->userId}");
        }

        if ($task['status'] == TaskStatusEnum::DONE->value) {
            throw new \Exception("The task with the status DONE can't be deleted.");
        }

        $this->taskRepository->delete($task);
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $request): Collection
    {
        $tasks = $this->taskRepository->getAllByUserId($request, $this->userId);

        if (empty($tasks->toArray())) {
            throw new TaskNotFoundException("Was not found any task for User: {$this->userId}");
        }

        return $tasks;
    }
}
