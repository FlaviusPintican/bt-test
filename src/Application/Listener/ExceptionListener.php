<?php declare(strict_types=1);

namespace App\Listener;

use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $headers = [];

        if ($exception instanceof HttpExceptionInterface) {
            $headers = $exception->getHeaders();
            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof ORMException || $exception instanceof ConnectionException) {
            $this->logger->error($message);
        }

        $response = new JsonResponse(['message' => $message], $statusCode, $headers);
        $response->setStatusCode($statusCode);
        $event->setResponse($response);
    }
}
