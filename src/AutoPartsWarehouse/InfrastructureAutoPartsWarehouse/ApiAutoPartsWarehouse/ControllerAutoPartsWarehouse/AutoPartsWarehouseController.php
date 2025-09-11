<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\ControllerAutoPartsWarehouse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingApi;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingFile;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingEmail;
use App\Sales\DomainSales\AdaptersInterface\AdapterAutoPartsWarehouseSalesInterface;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ApiProcessing\ApiProcessing;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\FileProcessing\FileProcessing;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\EmailProcessing\EmailProcessing;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail\AutoPartsEmail;
use App\Counterparty\DomainCounterparty\AdaptersInterface\AdapterAutoPartsWarehouseCounterpartyInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\ArrCounterparty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsApiType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsFaleType;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsEmailType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\SearchPaymentMethodQuery\FindOneByPaymentMethodQuery;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsManuallyType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\EditAutoPartsWarehouseType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehousePartNumbersInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\ArrPaymentMethodQuery;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SearchAutoPartsWarehouseType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\ShipmentAutoPartsToDate\FindByShipmentToDateQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditAutoPartsWarehouseQuery\FindAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindOneByPaymentMethodQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindByAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindIdAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\EditAutoPartsWarehouseCommand\EditAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\ArrAutoPartsWarehouseCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseArrCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DeleteAutoPartsWarehouseCommand\DeleteAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseFileCommandHandler;


class AutoPartsWarehouseController extends AbstractController
{
    /*функция сохранения в ручную входящих автодеталей */
    #[Route('saveAutoPartsManually', name: 'save_auto_parts_manually')]
    public function saveAutoPartsManually(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        SaveAutoPartsWarehouseCommandHandler $saveAutoPartsWarehouseCommandHandler,
        AdapterAutoPartsWarehousePartNumbersInterface $adapterAutoPartsWarehousePartNumbersInterface,
    ): Response {

        /*Подключаем формы*/
        $form_save_auto_parts_manually = $this->createForm(SaveAutoPartsManuallyType::class);

        /*Валидация формы*/
        $form_save_auto_parts_manually->handleRequest($request);

        $id = null;
        if ($form_save_auto_parts_manually->isSubmitted()) {
            if ($form_save_auto_parts_manually->isValid()) {

                try {
                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $part_number = $this->mapPartNumber(
                        $form_save_auto_parts_manually->getData()['part_number'],
                        $participant
                    );
                    $part_number = $adapterAutoPartsWarehousePartNumbersInterface
                        ->searchIdDetails($part_number);

                    $data_save_auto_parts_manually = $this->mapAutoPartsWarehouse(
                        $form_save_auto_parts_manually->getData()['id'],
                        $part_number,
                        $form_save_auto_parts_manually->getData()['id_counterparty'],
                        $form_save_auto_parts_manually->getData()['quantity'],
                        $form_save_auto_parts_manually->getData()['price'],
                        $form_save_auto_parts_manually->getData()['date_receipt_auto_parts_warehouse'],
                        $form_save_auto_parts_manually->getData()['id_payment_method'],
                        $part_number->getIdParticipant()
                    );

                    $id = $saveAutoPartsWarehouseCommandHandler
                        ->handler(new AutoPartsWarehouseCommand($data_save_auto_parts_manually));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@autoPartsWarehouse/saveAutoPartsManually.html.twig', [
            'title_logo' => 'Cохранить автодеталь вручную',
            'form_save_auto_parts_manually' => $form_save_auto_parts_manually->createView(),
            'id' => $id
        ]);
    }

    /*функция сохранения из фаил автодеталей на склад*/
    #[Route('saveAutoPartsFile', name: 'save_auto_parts_file')]
    public function saveAutoPartsFile(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        SaveAutoPartsWarehouseArrCommandHandler $saveAutoPartsWarehouseArrCommandHandler,
        AdapterAutoPartsWarehousePartNumbersInterface $adapterAutoPartsWarehousePartNumbersInterface,
        AdapterAutoPartsWarehouseCounterpartyInterface $adapterAutoPartsWarehouseCounterpartyInterface,
        FindOneByPaymentMethodQuery $findOneByPaymentMethodQuery
    ): Response {

        /*Подключаем формы*/
        $form_save_auto_parts_fale = $this->createForm(SaveAutoPartsFaleType::class);

        /*Валидация формы*/
        $form_save_auto_parts_fale->handleRequest($request);

        $saved = '';
        if ($form_save_auto_parts_fale->isSubmitted()) {
            if ($form_save_auto_parts_fale->isValid()) {

                try {

                    $file_data_array = FileProcessing::processing($form_save_auto_parts_fale->getData());

                    $participant = $adapterUserExtractionInterface->userExtraction();

                    $map_file_data = $this->mapData($file_data_array, $participant);

                    $arr_part_number = $adapterAutoPartsWarehousePartNumbersInterface
                        ->partNumberSearch($map_file_data['arr_part_number']);

                    $arr_counterparty = $adapterAutoPartsWarehouseCounterpartyInterface
                        ->counterpartySearch($map_file_data['arr_counterparty']);

                    $arr_method = $findOneByPaymentMethodQuery
                        ->paymentMethodQuery($map_file_data['arr_payment_method']);

                    $map_processed_data = $this->mapProcessedData(
                        $file_data_array,
                        $arr_part_number,
                        $arr_counterparty,
                        $arr_method
                    );

                    $saved = $saveAutoPartsWarehouseArrCommandHandler
                        ->handler(new ArrAutoPartsWarehouseCommand($map_processed_data));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@autoPartsWarehouse/saveAutoPartsFale.html.twig', [
            'title_logo' => 'Cохранить автодеталь через файл',
            'form_save_auto_parts_fale' => $form_save_auto_parts_fale->createView(),
            'saved' => $saved
        ]);
    }

    /*функция сохранения из электронного почтового ящика автодеталей на склад*/
    #[Route('/admin/saveAutoPartsImap', name: 'save_auto_parts_imap')]
    public function saveAutoPartsImap(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        SaveAutoPartsWarehouseArrCommandHandler $saveAutoPartsWarehouseArrCommandHandler,
        AdapterAutoPartsWarehousePartNumbersInterface $adapterAutoPartsWarehousePartNumbersInterface,
        AdapterAutoPartsWarehouseCounterpartyInterface $adapterAutoPartsWarehouseCounterpartyInterface,
        FindOneByPaymentMethodQuery $findOneByPaymentMethodQuery
    ): Response {

        /*Подключаем формы*/
        $form_save_auto_parts_email = $this->createForm(SaveAutoPartsEmailType::class);

        /*Валидация формы*/
        $form_save_auto_parts_email->handleRequest($request);

        $saved = '';
        $email_data_array = [];
        if ($form_save_auto_parts_email->isSubmitted()) {
            if ($form_save_auto_parts_email->isValid()) {
                try {

                    $email_data_array = EmailProcessing::processing();

                    if ($email_data_array != null) {
                        $participant = $adapterUserExtractionInterface->userExtraction();

                        $map_data_email = $this->mapData($email_data_array, $participant);

                        $arr_id_details = $adapterAutoPartsWarehousePartNumbersInterface
                            ->idPartNumbersSearch($map_data_email['arr_part_number']);

                        $arr_id_counterparty = $adapterAutoPartsWarehouseCounterpartyInterface
                            ->counterpartySearch($map_data_email['arr_counterparty']);

                        $arr_id_method = $findOneByPaymentMethodQuery
                            ->paymentMethodQuery($map_data_email['arr_payment_method']);

                        $map_processed_data = $this->mapProcessedData(
                            $email_data_array,
                            $arr_id_details,
                            $arr_id_counterparty,
                            $arr_id_method
                        );

                        $saved = $saveAutoPartsWarehouseArrCommandHandler
                            ->handler(new ArrAutoPartsWarehouseCommand($map_processed_data));
                    }
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
                //imap_close($email_data_array);
            }
        }


        return $this->render('@autoPartsWarehouse/saveAutoPartsEmail.html.twig', [
            'title_logo' => 'Cохранить автодеталь через Email',
            'form_save_auto_parts_email' => $form_save_auto_parts_email->createView(),
            'email_data_array' => $email_data_array,
            'saved' => $saved
        ]);
    }

    /*функция сохранения автодеталей на склад по API поставщиков*/
    #[Route('/admin/saveAutoPartsApi', name: 'save_auto_parts_api')]
    public function saveAutoPartsApi(
        Request $request,
        ApiProcessing $apiProcessing,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        AdapterAutoPartsWarehousePartNumbersInterface $adapterAutoPartsWarehousePartNumbersInterface,
        AdapterAutoPartsWarehouseCounterpartyInterface $adapterAutoPartsWarehouseCounterpartyInterface,
        FindOneByPaymentMethodQuery $findOneByPaymentMethodQuery,
        SaveAutoPartsWarehouseArrCommandHandler $saveAutoPartsWarehouseArrCommandHandler
    ): Response {

        /*Подключаем формы*/
        $form_save_auto_parts_api = $this->createForm(SaveAutoPartsApiType::class);

        /*Валидация формы*/
        $form_save_auto_parts_api->handleRequest($request);

        $saved = '';

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
            $arr_counterparty = $adapterAutoPartsWarehouseCounterpartyInterface->findByCounterparty($participant);
            $api_data_array = $apiProcessing->processing($arr_counterparty);

            if ($api_data_array != null) {
                $map_data_email = $this->mapData($api_data_array, $participant);

                $arr_id_details = $adapterAutoPartsWarehousePartNumbersInterface
                    ->idPartNumbersSearch($map_data_email['arr_part_number']);

                $arr_id_counterparty = $adapterAutoPartsWarehouseCounterpartyInterface
                    ->counterpartySearch($map_data_email['arr_counterparty']);

                $arr_id_method = $findOneByPaymentMethodQuery
                    ->paymentMethodQuery($map_data_email['arr_payment_method']);

                $map_processed_data = $this->mapProcessedData(
                    $api_data_array,
                    $arr_id_details,
                    $arr_id_counterparty,
                    $arr_id_method
                );

                $saved = $saveAutoPartsWarehouseArrCommandHandler
                    ->handler(new ArrAutoPartsWarehouseCommand($map_processed_data));
            }
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }


        return $this->render('@autoPartsWarehouse/saveAutoPartsApi.html.twig', [
            'title_logo' => 'Cохранить автодеталь через Api',
            'form_save_auto_parts_api' => $form_save_auto_parts_api->createView(),
            'api_data_array' => $api_data_array,
            'saved' => $saved
        ]);
    }



    /*Поиск автодеталей на сладе*/
    #[Route('searchAutoPartsWarehouse', name: 'search_auto_parts_warehouse')]
    public function searchAutoPartsWarehouse(
        Request $request,
        FindByAutoPartsWarehouseQueryHandler $findByAutoPartsWarehouseQueryHandler,
        FindByShipmentToDateQueryHandler $findByShipmentToDateQueryHandler,
    ): Response {

        /*Подключаем формы*/
        $form_search_auto_parts_warehouse = $this->createForm(SearchAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_search_auto_parts_warehouse->handleRequest($request);

        $search_data = $findByShipmentToDateQueryHandler->handler();

        if ($form_search_auto_parts_warehouse->isSubmitted()) {
            if ($form_search_auto_parts_warehouse->isValid()) {

                try {

                    $search_data = $findByAutoPartsWarehouseQueryHandler
                        ->handler(new AutoPartsWarehouseQuery($form_search_auto_parts_warehouse->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@autoPartsWarehouse/searchAutoPartsWarehouse.html.twig', [
            'title_logo' => 'Поиск автодетали на сладе',
            'form_search_auto_parts_warehouse' => $form_search_auto_parts_warehouse->createView(),
            'search_data' => $search_data,
        ]);
    }

    /*Редактирования наличия автодеталей на складе*/
    #[Route('editAutoPartsWarehouse', name: 'edit_auto_parts_warehouse')]
    public function editAutoPartsWarehouse(
        Request $request,
        FindIdAutoPartsWarehouseQueryHandler $findIdAutoPartsWarehouseQueryHandler,
        AdapterAutoPartsWarehouseSalesInterface $adapterAutoPartsWarehouseSalesInterface,
        FindAutoPartsWarehouseQueryHandler $findAutoPartsWarehouseQueryHandler,
        AdapterAutoPartsWarehousePartNumbersInterface $adapterAutoPartsWarehousePartNumbersInterface,
        EditAutoPartsWarehouseCommandHandler $editAutoPartsWarehouseCommandHandler,
    ): Response {

        /*Подключаем формы*/
        $form_edit_auto_parts_warehouse = $this->createForm(EditAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_edit_auto_parts_warehouse->handleRequest($request);

        try {

            $data_auto_parts_warehouse['id_auto_parts_warehouse'] = $findIdAutoPartsWarehouseQueryHandler
                ->handler(new AutoPartsWarehouseQuery($request->query->all()));

            $adapterAutoPartsWarehouseSalesInterface->salesDeleteEditAutoPartsWarehouse($data_auto_parts_warehouse);
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
            return $this->redirectToRoute('search_auto_parts_warehouse');
        }

        if (!empty($request->request->all())) {
            $data_form_edit_auto_parts_warehouse = $form_edit_auto_parts_warehouse->getData();
        }

        $id = null;
        if ($form_edit_auto_parts_warehouse->isSubmitted()) {
            if ($form_edit_auto_parts_warehouse->isValid()) {
                try {

                    $map_arr_id_details = [
                        'id_details' => $form_edit_auto_parts_warehouse->getData()['id_details']
                    ];
                    $arr_part_number = $adapterAutoPartsWarehousePartNumbersInterface->searchIdDetails($map_arr_id_details);

                    $map_arr_part_number = ['id_details' => $arr_part_number];
                    $data_edit_auto_parts_manually = array_replace(
                        $form_edit_auto_parts_warehouse->getData(),
                        $map_arr_part_number
                    );

                    $id = $editAutoPartsWarehouseCommandHandler
                        ->handler(new AutoPartsWarehouseCommand($data_edit_auto_parts_manually));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        if (empty($form_edit_auto_parts_warehouse->getData()) || !empty($arr_saving_information)) {
            try {

                $data_form_edit_auto_parts_warehouse = $findAutoPartsWarehouseQueryHandler
                    ->handler(new AutoPartsWarehouseQuery($request->query->all()));
            } catch (HttpException $e) {

                $this->errorMessageViaSession($e);
            }
        }

        return $this->render('@autoPartsWarehouse/editAutoPartsManually.html.twig', [
            'title_logo' => 'Изменение данных склада',
            'form_edit_auto_parts_warehouse' => $form_edit_auto_parts_warehouse->createView(),
            'id' => $id,
            'data_form_edit_auto_parts_warehouse' => $data_form_edit_auto_parts_warehouse
        ]);
    }

    /*Удаление автодетали*/
    #[Route('deleteAutoPartsWarehouse', name: 'delete_auto_parts_warehouse')]
    public function deleteAutoPartsWarehouse(
        Request $request,
        FindIdAutoPartsWarehouseQueryHandler $findIdAutoPartsWarehouseQueryHandler,
        AdapterAutoPartsWarehouseSalesInterface $adapterAutoPartsWarehouseSalesInterface,
        DeleteAutoPartsWarehouseCommandHandler $deleteAutoPartsWarehouseCommandHandler
    ): Response {
        try {

            $data_auto_parts_warehouse['id_auto_parts_warehouse'] = $findIdAutoPartsWarehouseQueryHandler
                ->handler(new AutoPartsWarehouseQuery($request->query->all()));

            $adapterAutoPartsWarehouseSalesInterface->salesDeleteEditAutoPartsWarehouse($data_auto_parts_warehouse);

            $deleteAutoPartsWarehouseCommandHandler
                ->handler(new AutoPartsWarehouseCommand($request->query->all()));

            $this->addFlash('delete', 'Поставка удалена');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('search_auto_parts_warehouse');
    }

    private function errorMessageViaSession(HttpException $e): static
    {

        $arr_validator_errors = json_decode($e->getMessage(), true);

        /* Выводим сообщения ошибки в форму через сессии  */
        foreach ($arr_validator_errors as $key => $value_errors) {
            if (is_array($value_errors)) {
                foreach ($value_errors as $key => $value) {
                    $message = $value;
                    $propertyPath = $key;
                }
            } else {
                $message = $value_errors;
                $propertyPath = $key;
            }

            $this->addFlash($propertyPath, $message);
        }

        return $this;
    }

    private function mapPartNumber(
        $part_number,
        $participant
    ): array {
        $arr_part_number['part_number'] = $part_number;
        $arr_part_number['id_participant'] = $participant;

        return $arr_part_number;
    }

    private function mapAutoPartsWarehouse(
        $id,
        $part_number,
        $counterparty,
        $quantity,
        $price,
        $date_receipt_auto_parts_warehouse,
        $payment_method,
        $participant
    ): array {
        $arr_auto_parts_warehouse['id'] = $id;
        $arr_auto_parts_warehouse['id_details'] = $part_number;
        $arr_auto_parts_warehouse['id_counterparty'] = $counterparty;
        $arr_auto_parts_warehouse['quantity'] = $quantity;
        $arr_auto_parts_warehouse['price'] = $price;
        $arr_auto_parts_warehouse['date_receipt_auto_parts_warehouse'] = $date_receipt_auto_parts_warehouse;
        $arr_auto_parts_warehouse['id_payment_method'] = $payment_method;
        $arr_auto_parts_warehouse['id_participant'] = $participant;

        return $arr_auto_parts_warehouse;
    }


    private function mapData(array $data, Participant $participant): array
    {

        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($data);

        $map_data = [];
        foreach ($data as $key => $value) {
            $arr_part_number[$key] =
                [
                    'part_number' => $value['part_number'],
                    'id_participant' => $participant
                ];
            $arr_counterparty[$key] =
                [
                    'name_counterparty' => $value['counterparty'],
                    'id_participant' => $participant
                ];
            $arr_payment_method[$key] =
                [
                    'method' => $value['payment_method'],
                    'id_participant' => $participant
                ];
        }
        $map_data = [
            'arr_part_number' => $arr_part_number,
            'arr_counterparty' => $arr_counterparty,
            'arr_payment_method' => $arr_payment_method
        ];
        return $map_data;
    }

    private function mapProcessedData(
        array $file_data_array,
        array $arr_id_details,
        array $arr_id_counterparty,
        array $arr_id_method,
    ): array {
        $arr_processed_data = [];
        foreach ($file_data_array as $key => $value) {

            $arr_processed_data[$key] = [
                'id' => null,
                'id_details' => $arr_id_details[$key]['part_number'],
                'id_counterparty' => $arr_id_counterparty[$key]['counterparty'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
                'date_receipt_auto_parts_warehouse' => $value['date_receipt_auto_parts_warehouse'],
                'id_payment_method' => $arr_id_method[$key]['payment_method'],
                'id_participant' => $arr_id_method[$key]['payment_method']->getIdParticipant()
            ];
        }

        return $arr_processed_data;
    }
}
