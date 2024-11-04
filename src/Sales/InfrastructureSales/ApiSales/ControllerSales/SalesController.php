<?php

namespace App\Sales\InfrastructureSales\ApiSales\ControllerSales;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Sales\InfrastructureSales\ApiSales\FormSales\SearchSalesType;
use App\Sales\InfrastructureSales\ApiSales\FormSales\AutoPartsSoldType;
use App\Sales\InfrastructureSales\ApiSales\FormSales\EditCartPartsType;
use App\Sales\InfrastructureSales\ApiSales\FormSales\CompletionSaleType;
use App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;
use App\Sales\ApplicationSales\QuerySales\ListCartAutoParts\FindByCartAutoPartsSoldQueryHandler;
use App\Sales\ApplicationSales\QuerySales\EditСartAutoPartsSold\FindСartAutoPartsSoldQueryHandler;
use App\Sales\ApplicationSales\CommandsSales\AddCartAutoPartsCommand\AddCartAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\EditCartAutoPartsCommand\EditCartAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\DeleteCartAutoPartsCommand\DeleteCartAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\CompletionSaleAutoPartsCommand\CompletionSaleAutoPartsCommandHandler;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\AdapterSales\AdapterSalesInterface;

class SalesController extends AbstractController
{

    /*Добавляет автодетали в корзину*/
    #[Route('/cartAutoPartsWarehouseSold', name: 'cart_auto_parts_warehouse_sold')]
    public function cartAutoPartsWarehouseSold(
        Request $request,
        AdapterSalesInterface $adapterSalesInterface,
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
            $car_parts_for_sale = $adapterSalesInterface
                ->findCartPartsWarehouse($request->query->all());
        } catch (HttpException $e) {

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
        if (!empty($cartAutoParts)) {

            foreach ($cartAutoParts as $key => $value) {

                $sum += ($value->getPriceSold());
            }
        }

        return $this->render('sales/cartAutoPartsWarehouseSold.html.twig', [
            'title_logo' => 'Добавить в корзину',
            'form_cart_auto_parts_warehouse_sold' => $form_cart_auto_parts_warehouse_sold->createView(),
            'cartAutoParts' => $cartAutoParts,
            'car_parts_for_sale' => $car_parts_for_sale,
            'sum_price_sold_cart_auto_parts' => $sum,
            'form_completion_sale' => $form_completion_sale->createView(),
        ]);
    }

    /*Корзина для продажи автодетали*/
    #[Route('/cartWarehouse', name: 'cart_warehouse')]
    public function cartWarehouse(
        Request $request,
        FindByCartAutoPartsSoldQueryHandler $findByCartAutoPartsSoldQueryHandler
    ): Response {

        /*Подключаем формы*/
        $form_completion_sale = $this->createForm(CompletionSaleType::class);

        /*Валидация формы */
        $form_completion_sale->handleRequest($request);

        $cartAutoParts = $findByCartAutoPartsSoldQueryHandler->handler();

        $sum = 0;
        if (!empty($cartAutoParts)) {

            foreach ($cartAutoParts as $key => $value) {

                $sum += ($value->getPriceSold());
            }
        }

        return $this->render('sales/cartWarehouse.html.twig', [
            'title_logo' => 'Корзина',
            'cartAutoParts' => $cartAutoParts,
            'sum_price_sold_cart_auto_parts' => $sum,
            'form_completion_sale' => $form_completion_sale->createView(),
        ]);
    }

    /*Редактирования автодеталей в корзине*/
    #[Route('/editСartAutoPartsWarehouseSold', name: 'edit_cart_auto_parts_warehouse_sold')]
    public function editСartAutoPartsWarehouseSold(
        Request $request,
        FindСartAutoPartsSoldQueryHandler $findСartAutoPartsSoldQueryHandler,
        EditCartAutoPartsCommandHandler $editCartAutoPartsCommandHandler
    ): Response {

        /*Подключаем формы*/
        $form_edit_cart_auto_parts_warehouse_sold = $this->createForm(EditCartPartsType::class);

        /*Валидация формы */
        $form_edit_cart_auto_parts_warehouse_sold->handleRequest($request);

        $valid_form_edit_cart = [];
        if (!empty($request->request->all())) {
            $valid_form_edit_cart = $request->request->all()['edit_cart_parts'];
        }

        if ($form_edit_cart_auto_parts_warehouse_sold->isSubmitted()) {
            if ($form_edit_cart_auto_parts_warehouse_sold->isValid()) {
                try {

                    $editCartAutoPartsCommandHandler
                        ->handler(new AutoPartsSoldCommand($form_edit_cart_auto_parts_warehouse_sold->getData()));

                    $this->addFlash('successfully', 'Успешное изменение данных в корзине');

                    return $this->redirectToRoute('cart_warehouse');
                } catch (HttpException $e) {

                    $arr_errors = json_decode($e->getMessage(), true);

                    /* Выводим сообщения ошибки в форму через сессии  */
                    foreach ($arr_errors as $key_errors => $value_errors) {
                        foreach ($value_errors as $key => $value) {
                            $message = $value;
                            $propertyPath = $key;
                            $this->addFlash($propertyPath, $message);
                        }
                    }
                }
            }
        }


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
            return $this->redirectToRoute('cart_warehouse');
        }

        //dd($valid_form_edit_cart);
        return $this->render('sales/editСartAutoPartsWarehouseSold.html.twig', [
            'title_logo' => 'Изменение данных склада',
            'form_edit_cart_auto_parts_warehouse_sold' => $form_edit_cart_auto_parts_warehouse_sold->createView(),
            'data_form_edit_cart_auto_parts_warehouse' => $data_form_edit_cart_auto_parts_warehouse,
            'valid_form_edit_cart' => $valid_form_edit_cart
        ]);
    }


    /*Удаление автодетали из корзины*/
    #[Route('/deleteСartAutoPartsWarehouseSold', name: 'delete_cart_auto_parts_warehouse_sold')]
    public function deleteСartAutoPartsWarehouseSold(
        Request $request,
        DeleteCartAutoPartsCommandHandler $deleteCartAutoPartsCommandHandler
    ): Response {

        try {

            $deleteCartAutoPartsCommandHandler
                ->handler(new AutoPartsSoldCommand($request->query->all()));
        } catch (HttpException $e) {

            $arr_errors = json_decode($e->getMessage(), true);

            /* Выводим сообщения ошибки в форму через сессии  */
            foreach ($arr_errors as $key => $value_error) {

                $message = $value_error;
                $propertyPath = $key;
                $this->addFlash($propertyPath, $message);
            }
            return $this->redirectToRoute('cart_warehouse');
        }

        $this->addFlash('delete', 'Поставка удалена');

        return $this->redirectToRoute('cart_warehouse');
    }

    /*Завершение продажи*/
    #[Route('/completionSale', name: 'completion_sale')]
    public function completionSale(
        CompletionSaleAutoPartsCommandHandler $completionSaleAutoPartsCommandHandler
    ): Response {

        try {

            $completionSaleAutoPartsCommandHandler->handler();
        } catch (HttpException $e) {

            $arr_errors = json_decode($e->getMessage(), true);

            /* Выводим сообщения ошибки в форму через сессии  */
            foreach ($arr_errors as $key => $value_error) {

                $message = $value_error;
                $propertyPath = $key;
                $this->addFlash($propertyPath, $message);
            }
            return $this->redirectToRoute('cart_warehouse');
        }

        $this->addFlash('successfully', 'Продано');

        return $this->redirectToRoute('cart_warehouse');
    }

    /*Поиск проданых деталей*/
    #[Route('/searchSales', name: 'search_sales')]
    public function searchSales(
        Request $request,
    ): Response {

        /*Подключаем формы*/
        $form_search_sales = $this->createForm(SearchSalesType::class);

        /*Валидация формы*/
        $form_search_sales->handleRequest($request);

        if ($form_search_sales->isSubmitted()) {
            if ($form_search_sales->isValid()) {
                new AutoPartsSoldQuery($form_search_sales->getData());
                dd($form_search_sales->getData());
            }
        }

        return $this->render('sales/searchSales.html.twig', [
            'title_logo' => 'Продажи',
            'form_search_sales' => $form_search_sales->createView(),
        ]);
    }
}
