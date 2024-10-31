<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\CompletionSaleAutoPartsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;

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
