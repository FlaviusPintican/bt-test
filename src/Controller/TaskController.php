<?php declare(strict_types=1);

namespace App\Controller;

use App\Services\TaskService;
use App\Services\TaskServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    private TaskServiceInterface $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function createTask(Request $request) : JsonResponse
    {
        return $this->json($this->taskService->createTask($request->request->all()));
    }

    public function getTasks(Request $request) : JsonResponse
    {
        return $this->json($this->taskService->getTasks($request->query->all()));
    }

    public function getTask(int $id) : JsonResponse
    {
        return $this->json($this->taskService->getTask($id));
    }
}
