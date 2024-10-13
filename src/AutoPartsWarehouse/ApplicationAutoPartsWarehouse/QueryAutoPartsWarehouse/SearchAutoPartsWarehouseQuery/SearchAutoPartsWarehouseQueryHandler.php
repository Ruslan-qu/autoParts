<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class SearchAutoPartsWarehouseQueryHandler
{

    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?array
    {
        $part_number_where = '';
        $arr_parameters = [];

        $id_part_name = $autoPartsWarehouseQuery->getIdPartName();
        if (!empty($id_part_name)) {

            $arr_parameters['id_part_name'] = $id_part_name;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_part_name = :id_part_name ';
            } else {
                $part_number_where .= 'AND d.id_part_name = :id_part_name ';
            }
        }

        $id_car_brand = $autoPartsWarehouseQuery->getIdCarBrand();
        if (!empty($id_car_brand)) {

            $arr_parameters['id_car_brand'] = $id_car_brand;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_car_brand = :id_car_brand ';
            } else {
                $part_number_where .= 'AND d.id_car_brand = :id_car_brand ';
            }
        }

        $id_side = $autoPartsWarehouseQuery->getIdSide();
        if (!empty($id_side)) {

            $arr_parameters['id_side'] = $id_side;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_side = :id_side ';
            } else {
                $part_number_where .= 'AND d.id_side = :id_side ';
            }
        }

        $id_body = $autoPartsWarehouseQuery->getIdBody();
        if (!empty($id_body)) {

            $arr_parameters['id_body'] = $id_body;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_body = :id_body ';
            } else {
                $part_number_where .= 'AND d.id_body = :id_body ';
            }
        }

        $id_axle = $autoPartsWarehouseQuery->getIdAxle();
        if (!empty($id_axle)) {

            $arr_parameters['id_axle'] = $id_axle;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_axle = :id_axle ';
            } else {
                $part_number_where .= 'AND d.id_axle = :id_axle ';
            }
        }

        if (empty($part_number_where) || empty($arr_parameters)) {
            return null;
        }

        $find_by_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findByAutoPartsWarehouse($arr_parameters, $part_number_where);

        return $find_by_auto_parts_warehouse;
    }
}
