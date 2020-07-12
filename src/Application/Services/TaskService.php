<?php declare(strict_types=1);

namespace App\Services;

use DateTime;
use App\Dto\PageDto;
use App\Dto\TaskDto;
use App\Entity\Task;
use App\Event\Event;
use App\Repository\TaskRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskService implements TaskServiceInterface
{
    private TaskRepository $taskRepository;
    private const TASK_CREATION = 'createTask';

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function createTask(array $payload) : Task
    {
        $event = new Event(new DateTime(), Event::EVENT_CREATION, uniqid());
        $action = $this->loadEvents()[$event->getName()];

        return $this->taskRepository->{$action}(new TaskDto($payload));
    }

    public function getTasks(array $payload) : array
    {
        return $this->taskRepository->getTasks(new PageDto($payload));
    }

    public function getTask(int $id) : Task
    {
        $task = $this->taskRepository->getTask($id);

        if (!($task instanceof Task)) {
            throw new NotFoundHttpException('Task not found');
        }

        return $task;
    }

    private function loadEvents() : array
    {
        return [
            Event::EVENT_CREATION => self::TASK_CREATION
        ];
    }
}
