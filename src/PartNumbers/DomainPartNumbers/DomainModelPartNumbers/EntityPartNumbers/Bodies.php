<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\BodiesRepository;

#[ORM\Entity(repositoryClass: BodiesRepository::class)]
class Bodies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $body = null;

    #[ORM\ManyToOne]
    private ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }

    public function setIdParticipant(?Participant $id_participant): static
    {
        $this->id_participant = $id_participant;

        return $this;
    }
}
