<?php declare(strict_types=1);

namespace App\Controller;

use App\Services\UserServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param int $userId
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function generatePassword(int $userId): JsonResponse
    {
        return $this->json([
            'password' => $this->userService->generatePassword($userId)
        ]);
    }

    /**
     * @param Request $request
     * @param int $userId
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function verifyPassword(Request $request, int $userId): JsonResponse
    {
        return $this->json([
            'is_valid_password' => $this->userService->verifyPassword(
                $userId,
                $request->request->get('password', '')
            )
        ]);
    }
}
