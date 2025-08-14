<?php
namespace App\Factory;

use App\Entity\Appointment;
use Zenstruck\Foundry\ModelFactory;

final class AppointmentFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'appointmentDate' => self::faker()->dateTimeBetween('+1 hour', '+30 days'),
            'client' => \App\Factory\ClientFactory::new(),
        ];
    }

    protected static function getClass(): string
    {
        return Appointment::class;
    }
}
