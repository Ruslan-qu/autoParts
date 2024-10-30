<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\ControllerAutoPartsWarehouse;

use App\Form\СartAutoPartsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\AutoPartsSoldType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\EditCartPartsType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\CompletionSaleType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsManuallyType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\EditAutoPartsWarehouseType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SearchAutoPartsWarehouseType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\AddCartAutoPartsCommand\AddCartAutoPartsCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditAutoPartsWarehouseQuery\FindAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditСartAutoPartsWarehouseSold\FindAutoPartsWarehouseQueryHandler2;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\FindByAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\SearchAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery\FindByCartAutoPartsSoldQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\EditAutoPartsWarehouseCommand\EditAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery\FindCartAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DeleteAutoPartsWarehouseCommand\DeleteAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditСartAutoPartsSold\FindСartAutoPartsSoldQueryHandler;


class AutoPartsWarehouseController extends AbstractController
{
    /*функция сохранения в ручную входящих автодеталей */
    #[Route('/saveAutoPartsManually', name: 'save_auto_parts_manually')]
    public function saveAutoPartsManually(
        Request $request,
        SaveAutoPartsWarehouseCommandHandler $saveAutoPartsWarehouseCommandHandler,
        AdapterAutoPartsWarehouseInterface $adapterAutoPartsWarehouseInterface
    ): Response {


        /*Подключаем формы*/
        $form_save_auto_parts_manually = $this->createForm(SaveAutoPartsManuallyType::class);

        /*Валидация формы*/
        $form_save_auto_parts_manually->handleRequest($request);


        $arr_saving_information = [];
        if ($form_save_auto_parts_manually->isSubmitted()) {
            if ($form_save_auto_parts_manually->isValid()) {

                $map_arr_id_details = [
                    'id_details' => $form_save_auto_parts_manually->getData()['id_details']
                ];

                $arr_part_number = $adapterAutoPartsWarehouseInterface
                    ->searchIdDetails($map_arr_id_details);
                $map_arr_part_number_manufactur = ['id_details' => $arr_part_number[0]];

                $data_save_auto_parts_manually = array_replace($form_save_auto_parts_manually->getData(), $map_arr_part_number_manufactur);

                $arr_saving_information['id'] = $saveAutoPartsWarehouseCommandHandler
                    ->handler(new AutoPartsWarehouseCommand($data_save_auto_parts_manually));

                if (empty($arr_saving_information['id'])) {

                    $this->addFlash('data_save', 'Поставка уже была сохранена');

                    return $this->redirectToRoute('save_auto_parts_manually');
                }
            }
        }

        return $this->render('autoPartsWarehouse/saveAutoPartsManually.html.twig', [
            'title_logo' => 'Cохранить автодеталь вручную',
            'form_save_auto_parts_manually' => $form_save_auto_parts_manually->createView(),
            'arr_saving_information' => $arr_saving_information
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

                $search_data[] = $findByAutoPartsWarehouseQueryHandler
                    ->handler(new AutoPartsWarehouseQuery($form_search_auto_parts_warehouse->getData()));
            }
        }

        return $this->render('autoPartsWarehouse/searchAutoPartsWarehouse.html.twig', [
            'title_logo' => 'Поиск автодетали на сладе',
            'form_search_auto_parts_warehouse' => $form_search_auto_parts_warehouse->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования наличия автодеталей на складе*/
    #[Route('/editAutoPartsWarehouse', name: 'edit_auto_parts_warehouse')]
    public function editAutoPartsWarehouse(
        Request $request,
        FindAutoPartsWarehouseQueryHandler $findAutoPartsWarehouseQueryHandler,
        AdapterAutoPartsWarehouseInterface $adapterAutoPartsWarehouseInterface,
        EditAutoPartsWarehouseCommandHandler $editAutoPartsWarehouseCommandHandler,
    ): Response {

        /*Подключаем формы*/
        $form_edit_auto_parts_warehouse = $this->createForm(EditAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_edit_auto_parts_warehouse->handleRequest($request);

        if (!empty($request->request->all())) {
            $data_form_edit_auto_parts_warehouse = $form_edit_auto_parts_warehouse->getData();
        }

        $arr_saving_information = [];
        if ($form_edit_auto_parts_warehouse->isSubmitted()) {
            if ($form_edit_auto_parts_warehouse->isValid()) {

                $map_arr_id_details = [
                    'id_details' => $form_edit_auto_parts_warehouse->getData()['id_details']
                ];

                $arr_part_number = $adapterAutoPartsWarehouseInterface->searchIdDetails($map_arr_id_details);
                $map_arr_part_number = ['id_details' => $arr_part_number[0]];

                $data_edit_auto_parts_manually = array_replace($form_edit_auto_parts_warehouse->getData(), $map_arr_part_number);

                $arr_saving_information = $editAutoPartsWarehouseCommandHandler
                    ->handler(new AutoPartsWarehouseCommand($data_edit_auto_parts_manually));
            }
        }

        if (empty($form_edit_auto_parts_warehouse->getData()) || !empty($arr_saving_information)) {

            $data_form_edit_auto_parts_warehouse = $findAutoPartsWarehouseQueryHandler
                ->handler(new AutoPartsWarehouseQuery($request->query->all()));
        }

        return $this->render('autoPartsWarehouse/editAutoPartsManually.html.twig', [
            'title_logo' => 'Изменение данных склада',
            'form_edit_auto_parts_warehouse' => $form_edit_auto_parts_warehouse->createView(),
            'arr_saving_information' => $arr_saving_information,
            'data_form_edit_auto_parts_warehouse' => $data_form_edit_auto_parts_warehouse
        ]);
    }

    /*Удаление автодетали*/
    #[Route('/deleteAutoPartsWarehouse', name: 'delete_auto_parts_warehouse')]
    public function deleteAutoPartsWarehouse(
        Request $request,
        DeleteAutoPartsWarehouseCommandHandler $deleteAutoPartsWarehouseCommandHandler
    ): Response {

        $deleteAutoPartsWarehouseCommandHandler
            ->handler(new AutoPartsWarehouseCommand($request->query->all()));

        $this->addFlash('delete', 'Поставка удалена');

        return $this->redirectToRoute('search_auto_parts_warehouse');
    }


    /*Корзина для продажи автодетали*/
    #[Route('/cartAutoPartsWarehouseSold', name: 'cart_auto_parts_warehouse_sold')]
    public function cartAutoPartsWarehouseSold(
        Request $request,
        FindCartAutoPartsWarehouseQueryHandler $findCartAutoPartsWarehouseQueryHandler,
        AddCartAutoPartsCommandHandler $addCartAutoPartsCommandHandler,
        FindByCartAutoPartsSoldQueryHandler $findByCartAutoPartsSoldQueryHandler
    ): Response {

        /*Подключаем формы*/
        $form_cart_auto_parts_warehouse_sold = $this->createForm(AutoPartsSoldType::class);
        $form_completion_sale = $this->createForm(CompletionSaleType::class);

        /*Валидация формы */
        $form_cart_auto_parts_warehouse_sold->handleRequest($request);
        $form_completion_sale->handleRequest($request);

        try {

            $car_parts_for_sale = $findCartAutoPartsWarehouseQueryHandler
                ->handler(new AutoPartsWarehouseQuery($request->query->all()));
        } catch (HttpException $e) {
            // dd($e->getMessage());
            $arr_errors = json_decode($e->getMessage(), true);
            /*Выводим сообщения ошибки*/

            foreach ($arr_errors as $key => $value_error) {
                $message = $value_error;
                $propertyPath = $key;
                $this->addFlash($propertyPath, $message);
            }
            return $this->redirectToRoute('search_auto_parts_warehouse');
        }

        if ($form_cart_auto_parts_warehouse_sold->isSubmitted()) {
            if ($form_cart_auto_parts_warehouse_sold->isValid()) {

                try {

                    $addCartAutoPartsCommandHandler
                        ->handler(new AutoPartsSoldCommand($form_cart_auto_parts_warehouse_sold->getData()));
                } catch (HttpException $e) {

                    $arr_validator_errors = json_decode($e->getMessage(), true);
                    /* Выводим сообщения ошибки в форму через сессии  */

                    foreach ($arr_validator_errors as $key => $value_arr_validator_errors) {
                        foreach ($value_arr_validator_errors as $key => $value) {
                            $message = $value;
                            $propertyPath = $key;
                            $this->addFlash($propertyPath, $message);
                        }
                    }
                }
            }
        }

        $cartAutoParts = $findByCartAutoPartsSoldQueryHandler->handler();

        $sum = 0;
        foreach ($cartAutoParts as $key => $value) {

            $sum += ($value->getPriceSold());
        }

        if ($form_completion_sale->isSubmitted()) {
            if ($form_completion_sale->isValid()) {
                dd($form_completion_sale->getData());
                try {

                    $addCartAutoPartsCommandHandler
                        ->handler(new AutoPartsSoldCommand($form_cart_auto_parts_warehouse_sold->getData()));
                } catch (HttpException $e) {

                    $arr_validator_errors = json_decode($e->getMessage(), true);
                    /* Выводим сообщения ошибки в форму через сессии  */

                    foreach ($arr_validator_errors as $key => $value_arr_validator_errors) {
                        foreach ($value_arr_validator_errors as $key => $value) {
                            $message = $value;
                            $propertyPath = $key;
                            $this->addFlash($propertyPath, $message);
                        }
                    }
                }
            }
        }

        //$saving_information = $deleteAutoPartsWarehouseCommandHandler
        //  ->handler(new AutoPartsWarehouseCommand($request->query->all()));

        return $this->render('autoPartsWarehouse/cartAutoPartsWarehouseSold.html.twig', [
            'title_logo' => 'Корзина',
            'form_cart_auto_parts_warehouse_sold' => $form_cart_auto_parts_warehouse_sold->createView(),
            'cartAutoParts' => $cartAutoParts,
            'car_parts_for_sale' => $car_parts_for_sale,
            'sum_price_sold_cart_auto_parts' => $sum,
            'form_completion_sale' => $form_completion_sale->createView(),
        ]);
    }

    /*Редактирования автодеталей в корзине*/
    #[Route('/editСartAutoPartsWarehouseSold', name: 'edit_cart_auto_parts_warehouse_sold')]
    public function editСartAutoPartsWarehouseSold(
        Request $request,
        FindСartAutoPartsSoldQueryHandler $findСartAutoPartsSoldQueryHandler,
    ): Response {

        /*Подключаем формы*/
        $form_edit_cart_auto_parts_warehouse_sold = $this->createForm(EditCartPartsType::class);

        /*Валидация формы */
        $form_edit_cart_auto_parts_warehouse_sold->handleRequest($request);

        if (!empty($request->request->all())) {
            $data_form_edit_cart_auto_parts_warehouse = $form_edit_cart_auto_parts_warehouse_sold->getData();
        }

        if ($form_edit_cart_auto_parts_warehouse_sold->isSubmitted()) {
            if ($form_edit_cart_auto_parts_warehouse_sold->isValid()) {

                $arr_saving_information = $editAutoPartsWarehouseCommandHandler
                    ->handler(new AutoPartsWarehouseCommand($form_edit_cart_auto_parts_warehouse_sold->getData()));
            }
        }

        if (empty($form_edit_cart_auto_parts_warehouse_sold->getData()) || !empty($arr_saving_information)) {
            try {

                $data_form_edit_cart_auto_parts_warehouse = $findСartAutoPartsSoldQueryHandler
                    ->handler(new AutoPartsSoldQuery($request->query->all()));
            } catch (HttpException $e) {

                $arr_errors = json_decode($e->getMessage(), true);
                /* Выводим сообщения ошибки в форму через сессии  */

                foreach ($arr_errors as $key => $value_error) {

                    $message = $value_error;
                    $propertyPath = $key;
                    $this->addFlash($propertyPath, $message);
                }
                return $this->redirectToRoute('cart_auto_parts_warehouse_sold');
            }
        }
        //dd($data_form_edit_cart_auto_parts_warehouse);
        return $this->render('autoPartsWarehouse/editСartAutoPartsWarehouseSold.html.twig', [
            'title_logo' => 'Изменение данных склада',
            'form_edit_cart_auto_parts_warehouse_sold' => $form_edit_cart_auto_parts_warehouse_sold->createView(),
            'data_form_edit_cart_auto_parts_warehouse' => $data_form_edit_cart_auto_parts_warehouse
        ]);
    }
}
