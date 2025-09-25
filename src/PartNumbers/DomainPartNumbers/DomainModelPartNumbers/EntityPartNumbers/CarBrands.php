<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\CarBrandsRepository;

#[ORM\Entity(repositoryClass: CarBrandsRepository::class)]
class CarBrands
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $car_brand = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'Cascade')]
    private ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarBrand(): ?string
    {
        return $this->car_brand;
    }

    public function setCarBrand(?string $car_brand): static
    {
        $this->car_brand = $car_brand;

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
