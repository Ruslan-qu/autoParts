<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOParticipantCommand;

use App\Participant\ApplicationParticipant\CommandsParticipant\DTOParticipantCommand\MapParticipantCommand;

final class ParticipantCommand extends MapParticipantCommand

{
    protected ?int $id = null;

    protected ?string $email = null;

    protected ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
