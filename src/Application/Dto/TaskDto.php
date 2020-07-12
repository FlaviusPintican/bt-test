<?php declare(strict_types=1);

namespace App\Dto;

use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmozart\Assert\Assert;

class TaskDto
{
    private string $name;
    private string $status;
    private string $description;
    private DateTime $createdAt;

    public function __construct(array $data)
    {
        if (!isset($data['name'], $data['status'], $data['description'])) {
            throw new BadRequestHttpException(
                'Required keys: ' .
                implode(',', array_diff(['name', 'status', 'description'], array_keys($data)))
            );
        }

        $this->validateData($data);

        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->description = $data['description'];
        $this->createdAt = new DateTime();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    private function validateData(array $data) : void
    {
        Assert::string($data['name'], 'Name is required');
        Assert::string($data['status'], 'Status is required');
        Assert::string($data['description'], 'Description is required');
        Assert::maxLength($data['name'], 25, 'Name is too long');
        Assert::maxLength($data['status'], 5, 'Status is too long');
        Assert::maxLength($data['description'], 255, 'Description is too long');
    }
}
