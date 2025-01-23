<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti;

use IMAP\Connection;
use DateTimeImmutable;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingEmailKazanavtozapchasti
{
    public function reading(Connection $imap, int $email_id)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $headers = imap_headerinfo($imap, $email_id);
        $input_errors->emptyData($headers);

        $counterparty_email = $this->counterpartyEmail($headers);

        $date_email = $this->dateEmail($headers);

        $payment_method_email = $this->paymentMethodEmail();

        $body = imap_base64(imap_fetchbody($imap, $email_id, 2));
        $part_umber_uantity_price_email = $this->partNumberQuantityPriceEmail($body);
        $data_email = [];
        foreach ($part_umber_uantity_price_email as $key => $value) {
            $value[] = $counterparty_email;
            $value[] = $date_email;
            $value[] = $payment_method_email;
            $data_email[$key] = $value;
        }
        dd($data_email);

        return $this->mapDataEmail($data_email);
    }

    private function mapDataEmail($data_file): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $data_file_csv = [];
        foreach ($data_file as $key => $value) {

            $input_errors->emptyFileCells($value['2']);
            $part_number = $value['2'];

            $input_errors->emptyFileCells($value['3']);
            $quantity = $value['3'];

            $input_errors->emptyFileCells($value['4']);
            if (strpos($value['4'], ',')) {
                $price = (float)str_replace(',', '.', $value['4']);
            } else {
                $price = (float)$value['4'];
            }

            $input_errors->emptyFileCells($value['5']);
            $counterparty = $value['5'];

            $input_errors->emptyFileCellsDate($value['6']);
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value['6']);

            $input_errors->emptyFileCells($value['7']);
            $payment_method = $value['7'];


            $data_file_csv[$key] =
                [
                    'part_number' => $part_number,
                    'quantity' => $quantity,
                    'price' => $price,
                    'counterparty' => $counterparty,
                    'date_receipt_auto_parts_warehouse' => $date_receipt_auto_parts_warehouse,
                    'payment_method' => $payment_method
                ];
        }
        return $data_file_csv;
    }

    private function dateEmail($headers): string
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($headers->MailDate);
        return $headers->MailDate;
    }

    private function counterpartyEmail($headers): string
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyHeadersFrom($headers, 0);

        $name_counterparty = strtolower(preg_replace(
            '#[\s\W_]#',
            '',
            $headers->from[0]->mailbox
        ));

        return $name_counterparty;
    }

    private function partNumberQuantityPriceEmail($body): array
    {

        preg_match_all(
            "/<tr(.*?)<\/tr>/",
            $body,
            $arr_tr,
            PREG_SET_ORDER
        );
        unset($arr_tr[0]);
        $matches_td = [];
        foreach ($arr_tr as $key_tr => $value_tr) {

            preg_match_all(
                "/<td>(<span.*?>)*(.*?)[ р\.<\/span>]*<\/td>/",
                $value_tr[1],
                $arr_td,
                PREG_SET_ORDER
            );
            unset(
                $arr_td[0],
                $arr_td[1],
                $arr_td[2],
                $arr_td[4],
                $arr_td[5],
                $arr_td[6],
                $arr_td[9],
                $arr_td[10],
                $arr_td[11],
                $arr_td[12]
            );

            $arr_value_td = [];
            foreach ($arr_td as $key => $value) { {
                    $arr_value_td[$key] = $value[2];
                }
            }

            $matches_td[$key_tr] = array_values($arr_value_td);
        }

        return $matches_td;
    }

    private function paymentMethodEmail(): int
    {

        return 2;
    }
}
