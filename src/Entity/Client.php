<?php
namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"appointment"}) */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=100) @Assert\NotBlank @Groups({"appointment"}) */
    private ?string $firstName = null;

    /** @ORM\Column(type="string", length=100) @Assert\NotBlank @Groups({"appointment"}) */
    private ?string $lastName = null;

    /** @ORM\Column(type="string", length=180) @Assert\NotBlank @Assert\Email @Groups({"appointment"}) */
    private ?string $email = null;

    public function getId(): ?int { return $this->id; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $v): self { $this->firstName = $v; return $this; }
    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $v): self { $this->lastName = $v; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $v): self { $this->email = $v; return $this; }

    public function getFullName(): string
    {
        return trim(($this->firstName ?? '').' '.($this->lastName ?? ''));
    }
}
