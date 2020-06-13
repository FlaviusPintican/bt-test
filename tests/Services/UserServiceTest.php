<?php declare(strict_types=1);

namespace App\Tests\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UserService;
use DateTime;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserServiceTest extends TestCase
{
    /**
     * @var MockObject|UserRepository
     */
    private $userRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function test_it_fails_because_user_not_found(): void
    {
        $this->mockUser($userId = 1);
        $userService = new UserService($this->userRepository);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('User with id %d is not found', $userId));

        $userService->generatePassword($userId);
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function test_it_generate_a_new_password(): void
    {
        $user = new User();
        $user->setPassword('test');
        $this->mockUser($userId = 1, $user);
        $this->userRepository->expects(static::once())
            ->method('save')
            ->with($user)
            ->willReturn($user);

        $userService = new UserService($this->userRepository);

        static::assertContains("$userId", $userService->generatePassword($userId));
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function test_it_fails_because_password_is_missing(): void
    {
        $userService = new UserService($this->userRepository);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Password is missing');

        $userService->verifyPassword(1, '');
    }

    /**
     * @dataProvider checkPasswordValidity
     * @param string $time
     * @param bool $isValidPassword
     * @throws Exception
     *
     * @return void
     */
    public function test_it_returns_password_validity_status(string $time, bool $isValidPassword): void
    {
        $user = new User();
        $user->setPassword(password_hash($password = 'test', PASSWORD_BCRYPT, ['cost' => 12]));
        $user->setExpiredAt(new DateTime($time));

        $this->mockUser($userId = 1, $user);

        $userService = new UserService($this->userRepository);

        static::assertEquals($isValidPassword, $userService->verifyPassword($userId, $password));
    }

    /**
     * @return array
     */
    public function checkPasswordValidity(): array
    {
        return [
            'valid password' => ['+2 minutes', true],
            'invalid password' => ['-1 minutes', false],
        ];
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->userRepository = null;
    }

    /**
     * @param int $userId
     * @param User|null $user
     * @return void
     */
    private function mockUser(int $userId, User $user = null): void
    {
        $this->userRepository->expects(static::once())
            ->method('find')
            ->with($userId)
            ->willReturn($user);
    }
}
