<?php
namespace App\Tests\Service;

use App\Entity\Appointment;
use App\Entity\Client;
use App\Service\AppointmentService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AppointmentServiceTest extends TestCase
{
    public function testSavePersistsAndFlushes()
    {
        $client = (new Client())->setFirstName('A')->setLastName('B')->setEmail('a@b.com');
        $a = (new Appointment())->setClient($client)->setAppointmentDate(new \DateTime('+1 day'));

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist')->with($a);
        $em->expects($this->once())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher->expects($this->once())->method('dispatch');

        $svc = new AppointmentService($em, $logger, $dispatcher);
        $svc->save($a);

        $this->assertInstanceOf(Appointment::class, $a);
    }
}
