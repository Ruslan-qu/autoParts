<?php

namespace App\Sales\ApplicationSales\CommandsSales\CompletionSaleAutoPartsCommand;

use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;

final class CompletionSaleAutoPartsCommandHandler
{

    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(): void
    {

        $arr_completion_sale = $this->autoPartsSoldRepositoryInterface->findByCompletionSale();

        $seed = (int)floor(time() / 2);

        foreach ($arr_completion_sale as $key => $value) {
            if ($value->getIdAutoPartsWarehouse()->getQuantity() == $value->getIdAutoPartsWarehouse()->getQuantitySold()) {
                $value->getIdAutoPartsWarehouse()->setSales(1);
            }

            $value->setIdSold($seed);
            $value->setSoldStatus(true);
        }

        $this->autoPartsSoldRepositoryInterface->sold();
    }
}
