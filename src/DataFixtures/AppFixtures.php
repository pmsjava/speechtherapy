<?php
namespace App\DataFixtures;

use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ClientFactory::createMany(20);
        AppointmentFactory::createMany(40);
        // Foundry sam persistuje i flushuje według konfiguracji
    }
}
