<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CreateCounterpartyQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;


final class CreateSearchPartNumbersQueryHandler
{
    private $part_numbers_from_manufacturers_repository;

    public function __construct(
        PartNumbersFromManufacturersRepository $partNumbersFromManufacturersRepository
    ) {
        $this->part_numbers_from_manufacturers_repository = $partNumbersFromManufacturersRepository;
    }

    public function handler(CreatePartNumbersQuery $createPartNumbersQuery): ?array
    {
        $where = '';
        $parameters = [];
        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersQuery->getPartNumber()
        ));
        if (!empty($part_number)) {

            $parameters['part_number'] = $part_number;
            $where = 'WHERE p.part_number = :part_number ';
        }

        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersQuery->getManufacturer()
        ));
        if (!empty($manufacturer)) {

            $parameters['manufacturer'] = $manufacturer;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.manufacturer = :manufacturer ';
            } else {
                $where .= 'AND p.manufacturer = :manufacturer ';
            }
        }

        $id_part_name = $createPartNumbersQuery->getIdPartName();
        if (!empty($id_part_name)) {

            $parameters['id_part_name'] = $id_part_name;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_part_name = :id_part_name ';
            } else {
                $where .= 'AND p.id_part_name = :id_part_name ';
            }
        }

        $id_car_brand = $createPartNumbersQuery->getIdCarBrand();
        if (!empty($id_car_brand)) {

            $parameters['id_car_brand'] = $id_car_brand;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_car_brand = :id_car_brand ';
            } else {
                $where .= 'AND p.id_car_brand = :id_car_brand ';
            }
        }

        $id_side = $createPartNumbersQuery->getIdSide();
        if (!empty($id_side)) {

            $parameters['id_side'] = $id_side;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_side = :id_side ';
            } else {
                $where .= 'AND p.id_side = :id_side ';
            }
        }

        $id_body = $createPartNumbersQuery->getIdBody();
        if (!empty($id_body)) {

            $parameters['id_body'] = $id_body;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_body = :id_body ';
            } else {
                $where .= 'AND p.id_body = :id_body ';
            }
        }

        $id_axle = $createPartNumbersQuery->getIdAxle();
        if (!empty($id_axle)) {

            $parameters['id_axle'] = $id_axle;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_axle = :id_axle ';
            } else {
                $where .= 'AND p.id_axle = :id_axle ';
            }
        }

        $id_in_stock = $createPartNumbersQuery->getIdInStock();
        if (!empty($id_in_stock)) {

            $parameters['id_in_stock'] = $id_in_stock;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_in_stock = :id_in_stock ';
            } else {
                $where .= 'AND p.id_in_stock = :id_in_stock ';
            }
        }

        $id_original_number = $createPartNumbersQuery->getIdOriginalNumber();
        if (!empty($id_original_number)) {

            $parameters['id_original_number'] = $id_original_number;
            if (!str_contains($where, 'WHERE')) {
                $where = 'WHERE p.id_original_number = :id_original_number ';
            } else {
                $where .= 'AND p.id_original_number = :id_original_number ';
            }
        }
        if (empty($where) || empty($parameters)) {
            return null;
        }
        //dd($parameters);
        $counterparty = $this->part_numbers_from_manufacturers_repository->findByPartNumbers($parameters, $where);
        dd($counterparty);
        return $counterparty;
    }
}
