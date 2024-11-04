<?php

namespace App\Sales\ApplicationSales\QuerySales\EditСartAutoPartsSold;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;


final class FindСartAutoPartsSoldQueryHandler
{
    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldQuery $autoPartsSoldQuery): ?AutoPartsSold
    {

        $id = $autoPartsSoldQuery->getId();

        if (empty($id)) {
            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $edit_find_cart_auto_parts_warehouse_sold = $this->autoPartsSoldRepositoryInterface->findСartAutoPartsWarehouseSold($id);

        if (empty($edit_find_cart_auto_parts_warehouse_sold)) {
            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $edit_find_cart_auto_parts_warehouse_sold[0];
    }
}
