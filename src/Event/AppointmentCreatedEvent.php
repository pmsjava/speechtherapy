<?php
namespace App\Event;

use App\Entity\Appointment;
use Symfony\Contracts\EventDispatcher\Event;

class AppointmentCreatedEvent extends Event
{
    public const NAME = 'appointment.created';

    public function __construct(private Appointment $appointment) {}

    public function getAppointment(): Appointment
    {
        return $this->appointment;
    }
}
