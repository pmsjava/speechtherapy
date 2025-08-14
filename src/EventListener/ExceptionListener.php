<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $this->logger->error('Unhandled exception', ['message' => $e->getMessage()]);
        if (str_starts_with($event->getRequest()->getPathInfo(), '/api')) {
            $event->setResponse(new JsonResponse(['error'=>'Internal Server Error'], 500));
        }
    }
}
