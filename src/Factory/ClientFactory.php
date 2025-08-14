<?php
namespace App\Factory;

use App\Entity\Client;
use Zenstruck\Foundry\ModelFactory;

final class ClientFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'firstName' => self::faker()->firstName(),
            'lastName'  => self::faker()->lastName(),
            'email'     => self::faker()->unique()->safeEmail(),
        ];
    }

    protected static function getClass(): string
    {
        return Client::class;
    }
}
