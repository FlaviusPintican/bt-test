<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\PageDto;
use App\Dto\TaskDto;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param TaskDto $taskDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return Task
     */
    public function createTask(TaskDto $taskDto) : Task
    {
        $task = Task::create($taskDto);

        if (null === $task->getId()) {
            $this->_em->persist($task);
        }

        $this->_em->flush();

        return $task;
    }

    public function getTasks(PageDto $pageDto) : array
    {
        return $this->findBy([], [], $pageDto->getOffset() * $pageDto->getLimit(), $pageDto->getLimit());
    }

    public function getTask(int $id) : ?object
    {
        return $this->find($id);
    }
}
