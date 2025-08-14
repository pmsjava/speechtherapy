<?php
namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"appointment"}) */
    private ?int $id = null;

    /** @ORM\Column(type="datetime") @Assert\NotBlank @Groups({"appointment"}) */
    private ?\DateTimeInterface $appointmentDate = null;

    /** @ORM\ManyToOne(targetEntity=Client::class, cascade={"persist"}) @ORM\JoinColumn(nullable=false) @Assert\Valid @Groups({"appointment"}) */
    private ?Client $client = null;

    public function getId(): ?int { return $this->id; }
    public function getAppointmentDate(): ?\DateTimeInterface { return $this->appointmentDate; }
    public function setAppointmentDate(\DateTimeInterface $d): self { $this->appointmentDate = $d; return $this; }
    public function getClient(): ?Client { return $this->client; }
    public function setClient(Client $c): self { $this->client = $c; return $this; }
}
