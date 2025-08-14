<?php
namespace App\Event;

use App\Entity\Client;
use Symfony\Contracts\EventDispatcher\Event;

class ClientRegisteredEvent extends Event
{
    public const NAME = 'client.registered';

    public function __construct(private Client $client) {}

    public function getClient(): Client
    {
        return $this->client;
    }
}
