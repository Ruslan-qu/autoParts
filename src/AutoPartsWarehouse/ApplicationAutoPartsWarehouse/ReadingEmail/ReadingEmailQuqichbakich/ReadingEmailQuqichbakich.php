<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailQuqichbakich;

use IMAP\Connection;
use DateTimeImmutable;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingEmailQuqichbakich
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
        $data_imap = [];
        foreach ($part_umber_uantity_price_email as $key => $value) {
            $value[] = $counterparty_email;
            $value[] = $date_email;
            $value[] = $payment_method_email;
            $data_imap[$key] = $value;
        }

        $uid = imap_uid($imap, '1');
        $mail_move = imap_mail_move($imap, $uid, '&BBoEPgRABDcEOAQ9BDA-', CP_UID);
        $input_errors->emptyData($mail_move);

        return $this->mapDataEmail($data_imap);
    }

    private function mapDataEmail($data_imap): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($data_imap);

        $data_email = [];
        foreach ($data_imap as $key => $value) {

            $input_errors->emptyDataEmail($value, 0);
            $part_number = $value[0];

            $input_errors->emptyDataEmail($value, 1);
            $quantity = $value[1];

            $input_errors->emptyDataEmail($value, 2);
            if (strpos($value[2], ',')) {
                $price = (float)str_replace(',', '.', $value[2]);
            } else {
                $price = (float)$value[2];
            }

            $input_errors->emptyDataEmail($value, 3);
            $counterparty = $value[3];

            $input_errors->emptyDataEmail($value, 4);
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value[4]);

            $input_errors->emptyDataEmail($value, 5);
            $payment_method = $value[5];


            $data_email[$key] =
                [
                    'part_number' => $part_number,
                    'quantity' => $quantity,
                    'price' => $price,
                    'counterparty' => $counterparty,
                    'date_receipt_auto_parts_warehouse' => $date_receipt_auto_parts_warehouse,
                    'payment_method' => $payment_method
                ];
        }

        return $data_email;
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
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($body);


        preg_match_all(
            "/<tr>[<\w>]{0}(.*?)<\/tr>/",
            $body,
            $arr_tr,
            PREG_SET_ORDER
        );
        $input_errors->emptyData($arr_tr);
        array_shift($arr_tr);
        array_pop($arr_tr);

        $matches_td = [];
        foreach ($arr_tr as $key_tr => $value_tr) {

            preg_match_all(
                "/<td.*?>(.*?)<\/td>/",
                $value_tr[1],
                $arr_td,
                PREG_SET_ORDER
            );

            unset(
                $arr_td[0],
                $arr_td[1],
                $arr_td[2],
                $arr_td[3],
                $arr_td[4],
                $arr_td[6],
                $arr_td[8],
                $arr_td[9],
                $arr_td[11],
                $arr_td[12]
            );
            $input_errors->emptyData($arr_td);
            $arr_value_td = [];
            foreach ($arr_td as $key => $value) { {
                    $arr_value_td[$key] = $value[1];
                }
            }

            $matches_td[$key_tr] = array_values($arr_value_td);
        }

        return $matches_td;
    }

    private function paymentMethodEmail(): string
    {

        return 'нал';
    }
}
