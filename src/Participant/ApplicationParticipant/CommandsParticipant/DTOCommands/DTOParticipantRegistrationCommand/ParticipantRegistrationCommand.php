<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantRegistrationCommand;

use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantRegistrationCommand\MapParticipantRegistrationCommand;

final class ParticipantRegistrationCommand extends MapParticipantRegistrationCommand

{
    protected ?int $id = null;

    protected ?string $email = null;

    protected array $roles = [];

    protected ?string $password = null;

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }
}
