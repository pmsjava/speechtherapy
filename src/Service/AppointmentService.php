<?php
namespace App\Service;

use App\Entity\Appointment;
use App\Event\AppointmentCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AppointmentService
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private EventDispatcherInterface $dispatcher
    ) {}

    public function save(Appointment $appointment): Appointment
    {
        $isNew = null === $appointment->getId();

        $this->em->persist($appointment);
        $this->em->flush();

        $this->logger->info('Appointment saved', [
            'id' => $appointment->getId(),
            'date' => $appointment->getAppointmentDate()?->format('c'),
            'client' => $appointment->getClient()?->getEmail()
        ]);

        if ($isNew) {
            $this->dispatcher->dispatch(new AppointmentCreatedEvent($appointment), AppointmentCreatedEvent::NAME);
        }

        return $appointment;
    }

    public function delete(Appointment $appointment): void
    {
        $id = $appointment->getId();
        $this->em->remove($appointment);
        $this->em->flush();
        $this->logger->info('Appointment deleted', ['id' => $id]);
    }
}
