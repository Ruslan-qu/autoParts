<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\ControllerAutoPartsWarehouse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\DTOAutoPartsFile\AutoPartsFile;
use App\Sales\InfrastructureSales\ApiSales\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseSalesInterface;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsFaleType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsManuallyType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\EditAutoPartsWarehouseType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehousePartNumbersInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\ArrPaymentMethodQuery;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SearchAutoPartsWarehouseType;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseCounterpartyInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsFileCommand\AutoPartsFileCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditAutoPartsWarehouseQuery\FindAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindOneByPaymentMethodQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindByAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindIdAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\EditAutoPartsWarehouseCommand\EditAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DeleteAutoPartsWarehouseCommand\DeleteAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseFileCommandHandler;


class AutoPartsWarehouseController extends AbstractController
{
    /*функция сохранения в ручную входящих автодеталей */
    #[Route('/saveAutoPartsManually', name: 'save_auto_parts_manually')]
    public function saveAutoPartsManually(
        Request $request,
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

                    $map_arr_id_details = [
                        'id_details' => $form_save_auto_parts_manually->getData()['id_details']
                    ];
                    $part_number = $adapterAutoPartsWarehousePartNumbersInterface
                        ->searchIdDetails($map_arr_id_details);
                    $map_arr_part_number_manufactur = ['id_details' => $part_number];
                    $data_save_auto_parts_manually = array_replace(
                        $form_save_auto_parts_manually->getData(),
                        $map_arr_part_number_manufactur
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
    #[Route('/saveAutoPartsFile', name: 'save_auto_parts_file')]
    public function saveAutoPartsFile(
        Request $request,
        SaveAutoPartsWarehouseFileCommandHandler $saveAutoPartsWarehouseFileCommandHandler,
        AdapterAutoPartsWarehousePartNumbersInterface $adapterAutoPartsWarehousePartNumbersInterface,
        AdapterAutoPartsWarehouseCounterpartyInterface $adapterAutoPartsWarehouseCounterpartyInterface,
        FindOneByPaymentMethodQueryHandler $findOneByPaymentMethodQueryHandler
    ): Response {

        /*Подключаем формы*/
        $form_save_auto_parts_fale = $this->createForm(SaveAutoPartsFaleType::class);

        /*Валидация формы*/
        $form_save_auto_parts_fale->handleRequest($request);

        $id = null;
        if ($form_save_auto_parts_fale->isSubmitted()) {
            if ($form_save_auto_parts_fale->isValid()) {

                $file_data_array = FactoryReadingFile::choiceReadingFile(new AutoPartsFile($form_save_auto_parts_fale->getData()));

                $map_file_data = $this->mapFileData($file_data_array);

                $arr_id_details = $adapterAutoPartsWarehousePartNumbersInterface
                    ->partNumberSearch($map_file_data['arr_part_number']);

                $arr_id_counterparty = $adapterAutoPartsWarehouseCounterpartyInterface
                    ->counterpartySearch($map_file_data['arr_counterparty']);
                // dd($arr_id_counterparty);
                $arr_id_payment_method = $findOneByPaymentMethodQueryHandler
                    ->handler(new ArrPaymentMethodQuery($map_file_data['arr_payment_method']));
                /*$id = $saveAutoPartsWarehouseFileCommandHandler
                    ->handler(new AutoPartsFile($form_save_auto_parts_fale->getData()));*/

                //$excel = file_get_contents($form_save_auto_parts_fale->getData()['file_save']);

                //dd($form_save_auto_parts_fale->getData());
            }
        }

        return $this->render('@autoPartsWarehouse/saveAutoPartsFale.html.twig', [
            'title_logo' => 'Cохранить автодеталь через файл',
            'form_save_auto_parts_fale' => $form_save_auto_parts_fale->createView(),
            'id' => $id
        ]);
    }

    /*Поиск автодеталей на сладе*/
    #[Route('/searchAutoPartsWarehouse', name: 'search_auto_parts_warehouse')]
    public function searchAutoPartsWarehouse(
        Request $request,
        FindByAutoPartsWarehouseQueryHandler $findByAutoPartsWarehouseQueryHandler
    ): Response {

        /*Подключаем формы*/
        $form_search_auto_parts_warehouse = $this->createForm(SearchAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_search_auto_parts_warehouse->handleRequest($request);

        $search_data = [];
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
    #[Route('/editAutoPartsWarehouse', name: 'edit_auto_parts_warehouse')]
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
    #[Route('/deleteAutoPartsWarehouse', name: 'delete_auto_parts_warehouse')]
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

    private function mapFileData(array $file_data_array): array
    {

        $map_file_data = [];
        foreach ($file_data_array as $key => $value) {
            $arr_part_number[$key] =
                [
                    'part_name' => $value['part_name'],
                    'manufacturer' => $value['manufacturer'],
                    'part_number' => $value['part_number']
                ];
            $arr_counterparty[$key] =
                [
                    'counterparty' => $value['counterparty']
                ];
            $arr_payment_method[$key] =
                [
                    'method' => $value['payment_method']
                ];
        }
        $map_file_data = [
            'arr_part_number' => $arr_part_number,
            'arr_counterparty' => $arr_counterparty,
            'arr_payment_method' => $arr_payment_method
        ];
        return $map_file_data;
    }
}
