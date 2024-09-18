<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery;

abstract class OriginalRoomsQuery
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
