<?php declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * @return void
     */
    public function testGetId(): void
    {
        static::assertNull($this->user->getId());
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testGetPassword(): void
    {
        static::assertNull($this->user->getPassword());

        $this->user->setPassword($password = 'test');

        static::assertEquals($password, $this->user->getPassword());
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testGetExpiredAt(): void
    {
        static::assertNull($this->user->getExpiredAt());

        $this->user->setExpiredAt($date = new DateTime());

        static::assertEquals($date, $this->user->getExpiredAt());
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
       $this->user = null;
    }
}
