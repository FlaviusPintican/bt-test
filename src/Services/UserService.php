<?php declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function generatePassword(int $userId): string
    {
        $password = $userId . uniqid();

        $this->userRepository->save(
           $this->getUser($userId)
            ->setPassword(password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]))
            ->setExpiredAt((new DateTime())->modify('+2 minutes'))
            ->setIsFirstUsed(false)
        );

        return $password;
    }

    /**
     * {@inheritDoc}
     */
    public function verifyPassword(int $userId, string $password): bool
    {
        if (strlen($password) === 0) {
            throw new BadRequestHttpException('Password is missing');
        };

        $user = $this->getUser($userId);

        if (null === $user->getExpiredAt()
            || $user->getExpiredAt()->getTimestamp() < (new DateTime())->getTimestamp()
            || !password_verify($password, $user->getPassword())
            || true === $user->getIsFirstUsed()) {
            return false;
        }

        $user->setIsFirstUsed(true);
        $this->userRepository->save($user);

        return true;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    private function getUser(int $id): User
    {
        $user = $this->userRepository->find($id);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User with id %d is not found', $id));
        }

        return $user;
    }
}
