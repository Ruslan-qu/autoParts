<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;


final class SearchPartNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersFromManufacturersRepository $partNumbersFromManufacturersRepository
    ) {}

    public function handler(PartNumbersQuery $partNumbersQuery): ?array
    {
        $where_parameter = '';
        $arr_parameters = [];
        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersQuery->getPartNumber()
        ));
        if (!empty($part_number)) {

            $arr_parameters['part_number'] = $part_number;
            $where_parameter = $this->whereParameter($where_parameter, 'part_number', 'p');
        }

        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersQuery->getManufacturer()
        ));
        if (!empty($manufacturer)) {

            $arr_parameters['manufacturer'] = $manufacturer;
            $where_parameter = $this->whereParameter($where_parameter, 'manufacturer', 'p');
        }

        $id_part_name = $partNumbersQuery->getIdPartName();
        if (!empty($id_part_name)) {

            $arr_parameters['id_part_name'] = $id_part_name;
            $where_parameter = $this->whereParameter($where_parameter, 'id_part_name', 'p');
        }

        $id_car_brand = $partNumbersQuery->getIdCarBrand();
        if (!empty($id_car_brand)) {

            $arr_parameters['id_car_brand'] = $id_car_brand;
            $where_parameter = $this->whereParameter($where_parameter, 'id_car_brand', 'p');
        }

        $id_side = $partNumbersQuery->getIdSide();
        if (!empty($id_side)) {

            $arr_parameters['id_side'] = $id_side;
            $where_parameter = $this->whereParameter($where_parameter, 'id_side', 'p');
        }

        $id_body = $partNumbersQuery->getIdBody();
        if (!empty($id_body)) {

            $arr_parameters['id_body'] = $id_body;
            $where_parameter = $this->whereParameter($where_parameter, 'id_body', 'p');
        }

        $id_axle = $partNumbersQuery->getIdAxle();
        if (!empty($id_axle)) {

            $arr_parameters['id_axle'] = $id_axle;
            $where_parameter = $this->whereParameter($where_parameter, 'id_axle', 'p');
        }

        $id_in_stock = $partNumbersQuery->getIdInStock();
        if (!empty($id_in_stock)) {

            $arr_parameters['id_in_stock'] = $id_in_stock;
            $where_parameter = $this->whereParameter($where_parameter, 'id_in_stock', 'p');
        }

        $id_original_number = $partNumbersQuery->getIdOriginalNumber();
        if (!empty($id_original_number)) {

            $arr_parameters['id_original_number'] = $id_original_number;
            $where_parameter = $this->whereParameter($where_parameter, 'id_original_number', 'p');
        }

        $this->inputErrorsPartNumbers->emptyDataTwo($where_parameter, $arr_parameters);


        $find_by_part_numbers = $this->partNumbersFromManufacturersRepository->findByPartNumbers($arr_parameters, $where_parameter);
        $this->inputErrorsPartNumbers->emptyEntity($find_by_part_numbers);

        return $find_by_part_numbers;
    }

    private function whereParameter(string $where_parameter, string $parameter, string $select): string
    {

        if (!str_contains($where_parameter, 'WHERE')) {
            $where_parameter = 'WHERE ' . $select . '.' . $parameter . '= :' . $parameter . ' ';
        } else {
            $where_parameter .= 'AND ' . $select . '.' . $parameter . '= :' . $parameter . ' ';
        }


        return $where_parameter;
    }
}
