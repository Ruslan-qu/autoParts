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
        $part_number_where = '';
        $arr_parameters = [];
        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersQuery->getPartNumber()
        ));
        if (!empty($part_number)) {

            $arr_parameters['part_number'] = $part_number;
            $part_number_where = 'WHERE p.part_number = :part_number ';
        }

        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersQuery->getManufacturer()
        ));
        if (!empty($manufacturer)) {

            $arr_parameters['manufacturer'] = $manufacturer;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.manufacturer = :manufacturer ';
            } else {
                $part_number_where .= 'AND p.manufacturer = :manufacturer ';
            }
        }

        $id_part_name = $createPartNumbersQuery->getIdPartName();
        if (!empty($id_part_name)) {

            $arr_parameters['id_part_name'] = $id_part_name;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_part_name = :id_part_name ';
            } else {
                $part_number_where .= 'AND p.id_part_name = :id_part_name ';
            }
        }

        $id_car_brand = $createPartNumbersQuery->getIdCarBrand();
        if (!empty($id_car_brand)) {

            $arr_parameters['id_car_brand'] = $id_car_brand;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_car_brand = :id_car_brand ';
            } else {
                $part_number_where .= 'AND p.id_car_brand = :id_car_brand ';
            }
        }

        $id_side = $createPartNumbersQuery->getIdSide();
        if (!empty($id_side)) {

            $arr_parameters['id_side'] = $id_side;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_side = :id_side ';
            } else {
                $part_number_where .= 'AND p.id_side = :id_side ';
            }
        }

        $id_body = $createPartNumbersQuery->getIdBody();
        if (!empty($id_body)) {

            $arr_parameters['id_body'] = $id_body;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_body = :id_body ';
            } else {
                $part_number_where .= 'AND p.id_body = :id_body ';
            }
        }

        $id_axle = $createPartNumbersQuery->getIdAxle();
        if (!empty($id_axle)) {

            $arr_parameters['id_axle'] = $id_axle;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_axle = :id_axle ';
            } else {
                $part_number_where .= 'AND p.id_axle = :id_axle ';
            }
        }

        $id_in_stock = $createPartNumbersQuery->getIdInStock();
        if (!empty($id_in_stock)) {

            $arr_parameters['id_in_stock'] = $id_in_stock;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_in_stock = :id_in_stock ';
            } else {
                $part_number_where .= 'AND p.id_in_stock = :id_in_stock ';
            }
        }

        $id_original_number = $createPartNumbersQuery->getIdOriginalNumber();
        if (!empty($id_original_number)) {

            $arr_parameters['id_original_number'] = $id_original_number;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE p.id_original_number = :id_original_number ';
            } else {
                $part_number_where .= 'AND p.id_original_number = :id_original_number ';
            }
        }
        if (empty($part_number_where) || empty($arr_parameters)) {
            return null;
        }

        $find_by_part_numbers = $this->part_numbers_from_manufacturers_repository->findByPartNumbers($arr_parameters, $part_number_where);

        return $find_by_part_numbers;
    }
}
