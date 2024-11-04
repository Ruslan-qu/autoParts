<?php

namespace App\Sales\ApplicationSales\QuerySales\SearchSalesQuery;

use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery\SalesQuery;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindBySalesQueryHandler
{

    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(SalesQuery $salesQuery): ?array
    {
        $part_number_where = '';
        $arr_parameters = [];

        $part_number = $salesQuery->getPartNumber();
        if (!empty($part_number)) {

            $arr_parameters['part_number'] = $part_number;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.part_number = :part_number ';
            } else {
                $part_number_where .= 'AND d.part_number = :part_number ';
            }
        }

        $original_number = $salesQuery->getOriginalNumber();
        if (!empty($original_number)) {

            $arr_parameters['original_number'] = $original_number;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE o.original_number = :original_number ';
            } else {
                $part_number_where .= 'AND o.original_number = :original_number ';
            }
        }

        $from_date_sold = $salesQuery->getFromDateSold();
        if (!empty($from_date_sold)) {

            $arr_parameters['from_date_sold'] = $from_date_sold;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE s.date_sold >= :from_date_sold ';
            } else {
                $part_number_where .= 'AND s.date_sold >= :from_date_sold ';
            }
        }

        $to_date_sold = $salesQuery->getToDateSold();
        if (!empty($to_date_sold)) {

            $arr_parameters['to_date_sold'] = $to_date_sold;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE s.date_sold <= :to_date_sold ';
            } else {
                $part_number_where .= 'AND s.date_sold <= :to_date_sold ';
            }
        }

        $id_part_name = $salesQuery->getIdPartName();
        if (!empty($id_part_name)) {

            $arr_parameters['id_part_name'] = $id_part_name;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_part_name = :id_part_name ';
            } else {
                $part_number_where .= 'AND d.id_part_name = :id_part_name ';
            }
        }

        $id_car_brand = $salesQuery->getIdCarBrand();
        if (!empty($id_car_brand)) {

            $arr_parameters['id_car_brand'] = $id_car_brand;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_car_brand = :id_car_brand ';
            } else {
                $part_number_where .= 'AND d.id_car_brand = :id_car_brand ';
            }
        }

        $id_side = $salesQuery->getIdSide();
        if (!empty($id_side)) {

            $arr_parameters['id_side'] = $id_side;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_side = :id_side ';
            } else {
                $part_number_where .= 'AND d.id_side = :id_side ';
            }
        }

        $id_body = $salesQuery->getIdBody();
        if (!empty($id_body)) {

            $arr_parameters['id_body'] = $id_body;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_body = :id_body ';
            } else {
                $part_number_where .= 'AND d.id_body = :id_body ';
            }
        }

        $id_axle = $salesQuery->getIdAxle();
        if (!empty($id_axle)) {

            $arr_parameters['id_axle'] = $id_axle;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE d.id_axle = :id_axle ';
            } else {
                $part_number_where .= 'AND d.id_axle = :id_axle ';
            }
        }

        $id_counterparty = $salesQuery->getIdCounterparty();
        if (!empty($id_counterparty)) {

            $arr_parameters['id_counterparty'] = $id_counterparty;
            if (!str_contains($part_number_where, 'WHERE')) {
                $part_number_where = 'WHERE a.id_counterparty = :id_counterparty ';
            } else {
                $part_number_where .= 'AND a.id_counterparty = :id_counterparty ';
            }
        }

        if (empty($part_number_where) || empty($arr_parameters)) {

            $arr_data_errors = ['Error' => 'Пустые данные'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $part_number_where .= 'AND a.sales = :sales ';
        $arr_parameters['sales'] = 1;
        //dd($arr_parameters);
        $find_by_sales = $this->autoPartsSoldRepositoryInterface
            ->findBySales($arr_parameters, $part_number_where);

        return $find_by_sales;
    }
}
