<?php

namespace App\Sales\ApplicationSales\QuerySales\SearchSalesQuery;

use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery\SalesQuery;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;

final class FindBySalesQueryHandler
{

    public function __construct(
        private InputErrorsSales $inputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(SalesQuery $salesQuery): ?array
    {
        $where_parameter = '';
        $arr_parameters = [];

        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $salesQuery->getPartNumber()
        ));
        if (!empty($part_number)) {

            $arr_parameters['part_number'] = $part_number;
            $where_parameter = $this->whereParameter($where_parameter, 'part_number', 'd');
        }

        $original_number = strtolower(preg_replace(
            '#\s#',
            '',
            $salesQuery->getOriginalNumber()
        ));
        if (!empty($original_number)) {

            $arr_parameters['original_number'] = $original_number;
            $where_parameter = $this->whereParameter($where_parameter, 'original_number', 'o');
        }

        $from_date_sold = $salesQuery->getFromDateSold();
        if (!empty($from_date_sold)) {

            $arr_parameters['from_date_sold'] = $from_date_sold;
            $where_parameter = $this->whereParameterDate($where_parameter, 'date_sold', 'from_date_sold', 's', '>=');
        }

        $to_date_sold = $salesQuery->getToDateSold();
        if (!empty($to_date_sold)) {

            $arr_parameters['to_date_sold'] = $to_date_sold;
            $where_parameter = $this->whereParameterDate($where_parameter, 'date_sold', 'to_date_sold', 's', '<=');
        }

        $id_part_name = $salesQuery->getIdPartName();
        if (!empty($id_part_name)) {

            $arr_parameters['id_part_name'] = $id_part_name;
            $where_parameter = $this->whereParameter($where_parameter, 'id_part_name', 'd');
        }

        $id_car_brand = $salesQuery->getIdCarBrand();
        if (!empty($id_car_brand)) {

            $arr_parameters['id_car_brand'] = $id_car_brand;
            $where_parameter = $this->whereParameter($where_parameter, 'id_car_brand', 'd');
        }

        $id_side = $salesQuery->getIdSide();
        if (!empty($id_side)) {

            $arr_parameters['id_side'] = $id_side;
            $where_parameter = $this->whereParameter($where_parameter, 'id_side', 'd');
        }

        $id_body = $salesQuery->getIdBody();
        if (!empty($id_body)) {

            $arr_parameters['id_body'] = $id_body;
            $where_parameter = $this->whereParameter($where_parameter, 'id_body', 'd');
        }

        $id_axle = $salesQuery->getIdAxle();
        if (!empty($id_axle)) {

            $arr_parameters['id_axle'] = $id_axle;
            $where_parameter = $this->whereParameter($where_parameter, 'id_axle', 'd');
        }

        $id_counterparty = $salesQuery->getIdCounterparty();
        if (!empty($id_counterparty)) {

            $arr_parameters['id_counterparty'] = $id_counterparty;
            $where_parameter = $this->whereParameter($where_parameter, 'id_counterparty', 'a');
        }

        $this->inputErrorsSales->emptyDataTwo($where_parameter, $arr_parameters);

        $where_parameter .= 'AND s.sold_status = :sold_status ';
        $arr_parameters['sold_status'] = true;

        $find_by_sales = $this->autoPartsSoldRepositoryInterface
            ->findBySales($arr_parameters, $where_parameter);
        $this->inputErrorsSales->emptyEntity($find_by_sales);

        return $find_by_sales;
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

    private function whereParameterDate(string $where_parameter, string $parameter1, string $parameter2, string $select, $operator): string
    {

        if (!str_contains($where_parameter, 'WHERE')) {
            $where_parameter = 'WHERE ' . $select . '.' . $parameter1 . $operator . ' :' . $parameter2 . ' ';
        } else {
            $where_parameter .= 'AND ' . $select . '.' . $parameter1 . $operator . ' :' . $parameter2 . ' ';
        }

        return $where_parameter;
    }
}
