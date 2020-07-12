<?php declare(strict_types=1);

namespace App\Event;

use DateTime;

class Event
{
    const EVENT_CREATION = 'TASK_CREATION';

    private string $name;
    private string $id;
    private DateTime $date;

    public function __construct(DateTime $date, string $name, string $id)
    {
        $this->date = $date;
        $this->name = $name;
        $this->id = $id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getDate() : DateTime
    {
        return $this->date;
    }
}
