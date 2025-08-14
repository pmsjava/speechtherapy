<?php
namespace App\EventListener;

use App\Event\AppointmentCreatedEvent;
use Psr\Log\LoggerInterface;

class AppointmentCreatedListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function onAppointmentCreated(AppointmentCreatedEvent $event): void
    {
        $a = $event->getAppointment();
        $this->logger->info(sprintf(
            'New appointment created for %s at %s',
            $a->getClient()->getFullName(),
            $a->getAppointmentDate()->format('Y-m-d H:i')
        ));
    }
}
