<?php declare(strict_types=1);

namespace App\Services;

use App\Entity\Task;

interface TaskServiceInterface
{
    public function createTask(array $payload) : Task;
    public function getTasks(array $payload) : array;
    public function getTask(int $id) : Task;
}