<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Dto\TaskDto;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Docler\Infrastructure\Repository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private string $status;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private DateTime $createdAt;

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function setStatus(string $status) : void
    {
        $this->status = $status;
    }

    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }

    public static function create(TaskDto $taskDto): self
    {
        $task = new Task();
        $task->setName($taskDto->getName());
        $task->setStatus($taskDto->getStatus());
        $task->setDescription($taskDto->getDescription());
        $task->setCreatedAt($taskDto->getCreatedAt());

        return $task;
    }

    private function __construct()
    {

    }

    private function __clone()
    {

    }
}
