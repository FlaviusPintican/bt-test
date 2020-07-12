<?php declare(strict_types=1);

namespace App\Dto;

class PageDto
{
    private int $offset;
    private int $limit;

    public function __construct(array $data)
    {
        $this->offset = $data['offset'] ?? 0;
        $this->limit = $data['limit'] ?? 25;
    }

    public function getOffset() : int
    {
        return $this->offset;
    }

    public function getLimit() : int
    {
        return $this->limit;
    }
}