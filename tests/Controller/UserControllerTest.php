<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\UserController;
use App\Services\UserServiceInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class UserControllerTest extends TestCase
{
    /**
     * @var UserServiceInterface|MockObject
     */
    private $userService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->userService = $this->createMock(UserServiceInterface::class);
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testItGenerateNewPassword(): void
    {
        $this->userService->expects(static::once())
            ->method('generatePassword')
            ->with($userId = 1)
            ->willReturn($password = sha1(random_bytes(12)));

        $userController = new UserController($this->userService);
        $userController->setContainer($this->mockContainer());
        $response = json_decode($userController->generatePassword($userId)->getContent(), true);

        static::assertEquals($password, $response['password']);
    }

    /**
     * @dataProvider checkPasswordValidity
     * @param bool $isValidPassword
     * @throws Exception
     *
     * @return void
     */
    public function testItReturnsPasswordValidityStatus(bool $isValidPassword): void
    {
        $this->userService->expects(static::once())
            ->method('verifyPassword')
            ->with($userId = 1, $password = sha1(random_bytes(12)))
            ->willReturn($isValidPassword);

        $userController = new UserController($this->userService);
        $userController->setContainer($this->mockContainer());

        /** @var Request|MockObject $request */
        $request = $this->createMock(Request::class);

        /** @var ParameterBag|MockObject $parameterBag */
        $parameterBag = $this->createMock(ParameterBag::class);
        $parameterBag->expects(static::once())
            ->method('get')
            ->with('password', '')
            ->willReturn($password);

        $request->request = $parameterBag;
        $response = json_decode($userController->verifyPassword($request, $userId)->getContent(), true);

        static::assertEquals($isValidPassword, $response['is_valid_password']);
    }

    /**
     * @return array
     */
    public function checkPasswordValidity(): array
    {
        return [
            'valid password' => [true],
            'invalid password' => [false],
        ];
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->userService = null;
    }

    /**
     * @return MockObject|ContainerInterface
     */
    private function mockContainer(): MockObject
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(static::once())
            ->method('has')
            ->willReturn(null);

        return $container;
    }
}
