<?php declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use Exception;

interface UserServiceInterface
{
    /**
     * @param int $userId
     * @throws Exception
     *
     * @return string
     */
    public function generatePassword(int $userId): string;

    /**
     * @param int $userId
     * @param string $password
     * @throws Exception
     *
     * @return bool
     */
    public function verifyPassword(int $userId, string $password): bool;
}
