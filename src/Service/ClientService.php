<?php
namespace App\Service;

use App\Entity\Client;
use App\Event\ClientRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ClientService
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcherInterface $dispatcher
    ) {}

    public function upsert(Client $client): Client
    {
        $isNew = null === $client->getId();

        $this->em->persist($client);
        $this->em->flush();

        if ($isNew) {
            $this->dispatcher->dispatch(new ClientRegisteredEvent($client), ClientRegisteredEvent::NAME);
        }

        return $client;
    }
}
