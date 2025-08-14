<?php
namespace App\EventListener;

use App\Event\ClientRegisteredEvent;
use Psr\Log\LoggerInterface;

class ClientRegisteredListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function onClientRegistered(ClientRegisteredEvent $event): void
    {
        $c = $event->getClient();
        $this->logger->info(sprintf(
            'New client registered: %s (%s)',
            $c->getFullName(),
            $c->getEmail()
        ));
    }
}
