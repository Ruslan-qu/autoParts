<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\BodiesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BodiesRepository::class)]
class Bodies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $body = null;

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
}
