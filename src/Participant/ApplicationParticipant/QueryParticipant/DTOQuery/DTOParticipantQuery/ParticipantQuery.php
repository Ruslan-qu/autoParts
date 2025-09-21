<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery;

use App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery\MapParticipantQuery;

final class ParticipantQuery extends MapParticipantQuery

{
    protected ?int $id = null;

    protected ?string $email = null;

    protected array $roles = [];

    protected bool $isVerified = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRoles(): array
    {

        return $this->roles;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }
}
