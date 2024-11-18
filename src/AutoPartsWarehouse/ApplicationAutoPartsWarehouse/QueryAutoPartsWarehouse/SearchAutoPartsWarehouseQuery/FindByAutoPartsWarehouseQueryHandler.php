<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindByAutoPartsWarehouseQueryHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?array
    {
        $where_parameter = '';
        $arr_parameters = [];

        $id_part_name = $autoPartsWarehouseQuery->getIdPartName();

        if (!empty($id_part_name)) {

            $arr_parameters['id_part_name'] = $id_part_name;
            $where_parameter = $this->whereParameter($where_parameter, 'id_part_name', 'd');
        }

        $id_car_brand = $autoPartsWarehouseQuery->getIdCarBrand();

        if (!empty($id_car_brand)) {

            $arr_parameters['id_car_brand'] = $id_car_brand;
            $where_parameter = $this->whereParameter($where_parameter, 'id_car_brand', 'd');
        }

        $id_side = $autoPartsWarehouseQuery->getIdSide();

        if (!empty($id_side)) {

            $arr_parameters['id_side'] = $id_side;
            $where_parameter = $this->whereParameter($where_parameter, 'id_side', 'd');
        }

        $id_body = $autoPartsWarehouseQuery->getIdBody();

        if (!empty($id_body)) {

            $arr_parameters['id_body'] = $id_body;
            $where_parameter = $this->whereParameter($where_parameter, 'id_body', 'd');
        }

        $id_axle = $autoPartsWarehouseQuery->getIdAxle();

        if (!empty($id_axle)) {

            $arr_parameters['id_axle'] = $id_axle;
            $where_parameter = $this->whereParameter($where_parameter, 'id_axle', 'd');
        }

        $this->inputErrorsAutoPartsWarehouse->emptyDataTwo($where_parameter, $arr_parameters);

        $where_parameter .= 'AND a.sales = :sales ';
        $arr_parameters['sales'] = 0;

        $find_by_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findByAutoPartsWarehouse($arr_parameters, $where_parameter);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($find_by_auto_parts_warehouse);

        return $find_by_auto_parts_warehouse;
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
