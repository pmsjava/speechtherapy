<?php
namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, Appointment::class); }

    public function findUpcoming(int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.appointmentDate >= :now')->setParameter('now', new \DateTime())
            ->orderBy('a.appointmentDate', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }
}
