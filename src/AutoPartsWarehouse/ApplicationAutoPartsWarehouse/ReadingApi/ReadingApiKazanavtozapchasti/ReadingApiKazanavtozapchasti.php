<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiKazanavtozapchasti;

use DateTimeImmutable;
use Symfony\Component\HttpClient\HttpClient;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingApiKazanavtozapchasti
{
    public function reading($client, $counterpartyApi): ?array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;

        $response = $client->request(
            'GET',
            'https://free-e-store-api.onrender.com/api/v1/products'
        );

        $statusCode = $response->getStatusCode();
        $input_errors->statusCode($statusCode);

        $contentHeadersDate = $response->getHeaders()['date']['0'];

        $content = $response->getContent();
        $contentProducts = $response->toArray()['data']['products'];

        $payment_method_api = $this->paymentMethod();


        return $this->mapDataApi($input_errors, $counterpartyApi, $contentHeadersDate, $contentProducts, $payment_method_api);
    }

    private function mapDataApi($input_errors, $counterpartyApi, $contentHeadersDate, $contentProducts, $payment_method_api): array
    {

        $data_api = [];
        foreach ($contentProducts as $key => $value) {

            $input_errors->emptyData($value['name']);
            $part_number = $value['name'];

            $input_errors->emptyData($value['ref']);
            $quantity = $value['ref'];

            $input_errors->emptyData($value['realPrice']);
            if (strpos($value['realPrice'], ',')) {
                $price = (float)str_replace(',', '.', $value['realPrice']);
            } else {
                $price = (float)$value['realPrice'];
            }

            $input_errors->emptyData($counterpartyApi);
            $counterparty = $counterpartyApi;

            $input_errors->emptyData($contentHeadersDate);
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($contentHeadersDate);

            $input_errors->emptyData($payment_method_api);
            $payment_method = $payment_method_api;


            $data_api[$key] =
                [
                    'part_number' => $part_number,
                    'quantity' => $quantity,
                    'price' => $price,
                    'counterparty' => $counterparty,
                    'date_receipt_auto_parts_warehouse' => $date_receipt_auto_parts_warehouse,
                    'payment_method' => $payment_method
                ];
        }

        return $data_api;
    }

    private function paymentMethod(): string
    {

        return 'нал';
    }
}
