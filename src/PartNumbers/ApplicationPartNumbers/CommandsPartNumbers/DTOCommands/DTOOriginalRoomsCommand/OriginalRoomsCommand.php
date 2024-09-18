<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

abstract class OriginalRoomsCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {

        $original_number = $data['id_original_number'];

        if (!empty($original_number)) {

            $key_original_number = 'original_number';
            $this->$key_original_number = (string)$original_number;
        }
    }
}
