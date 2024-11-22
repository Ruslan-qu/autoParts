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
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery\SalesQuery;
use App\Sales\InfrastructureSales\ApiSales\FormSales\EditSalesAutoPartsType;
use App\Sales\ApplicationSales\QuerySales\SearchSalesQuery\FindBySalesQueryHandler;
use App\Sales\ApplicationSales\QuerySales\SalesToDate\FindBySalesToDateQueryHandler;
use App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;
use App\Sales\ApplicationSales\QuerySales\ListCartAutoParts\FindByCartAutoPartsSoldQueryHandler;
use App\Sales\ApplicationSales\QuerySales\EditSalesAutoParts\FindOneBySalesAutoPartsQueryHandler;
use App\Sales\ApplicationSales\QuerySales\EditСartAutoPartsSold\FindСartAutoPartsSoldQueryHandler;
use App\Sales\ApplicationSales\CommandsSales\AddCartAutoPartsCommand\AddCartAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\EditCartAutoPartsCommand\EditCartAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\EditSalesAutoPartsCommand\EditSalesAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\DeleteCartAutoPartsCommand\DeleteCartAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\DeleteSalesAutoPartsCommand\DeleteSalesAutoPartsCommandHandler;
use App\Sales\ApplicationSales\CommandsSales\CompletionSaleAutoPartsCommand\CompletionSaleAutoPartsCommandHandler;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterSales\AdapterSalesInterface;

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

            $this->errorMessageViaSession($e);

            return $this->redirectToRoute('search_auto_parts_warehouse');
        }

        if ($form_cart_auto_parts_warehouse_sold->isSubmitted()) {
            if ($form_cart_auto_parts_warehouse_sold->isValid()) {

                try {

                    $addCartAutoPartsCommandHandler
                        ->handler(new AutoPartsSoldCommand($form_cart_auto_parts_warehouse_sold->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        $cartAutoParts = $findByCartAutoPartsSoldQueryHandler->handler();

        $sum = $this->sumCartAutoPartsSales($cartAutoParts);

        return $this->render('@sales/cartAutoPartsWarehouseSold.html.twig', [
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

        $sum = $this->sumCartAutoPartsSales($cartAutoParts);


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

                    $this->errorMessageViaSession($e);
                }
            }
        }

        try {

            $data_form_edit_cart_auto_parts_warehouse = $findСartAutoPartsSoldQueryHandler
                ->handler(new AutoPartsSoldQuery($request->query->all()));
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
            return $this->redirectToRoute('cart_warehouse');
        }

        return $this->render('@sales/editСartAutoPartsWarehouseSold.html.twig', [
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
            $this->addFlash('delete', 'Поставка удалена');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('cart_warehouse');
    }

    /*Завершение продажи*/
    #[Route('/completionSale', name: 'completion_sale')]
    public function completionSale(
        CompletionSaleAutoPartsCommandHandler $completionSaleAutoPartsCommandHandler
    ): Response {

        try {

            $completionSaleAutoPartsCommandHandler->handler();
            $this->addFlash('successfully', 'Продано');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('cart_warehouse');
    }

    /*Поиск проданых деталей*/
    #[Route('/searchSales', name: 'search_sales')]
    public function searchSales(
        Request $request,
        FindBySalesQueryHandler $findBySalesQueryHandler,
        FindBySalesToDateQueryHandler $findBySalesToDateQueryHandler
    ): Response {

        /*Подключаем формы*/
        $form_search_sales = $this->createForm(SearchSalesType::class);

        /*Валидация формы*/
        $form_search_sales->handleRequest($request);

        $list_sales_auto_parts = $findBySalesToDateQueryHandler->handler();

        if ($form_search_sales->isSubmitted()) {
            if ($form_search_sales->isValid()) {
                try {

                    $list_sales_auto_parts = $findBySalesQueryHandler
                        ->handler(new SalesQuery($form_search_sales->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }
        //dd($list_sales_auto_parts);
        return $this->render('@sales/searchSales.html.twig', [
            'title_logo' => 'Продажи',
            'form_search_sales' => $form_search_sales->createView(),
            'list_sales_auto_parts' => $list_sales_auto_parts
        ]);
    }

    /*Редактирования автодеталей в продаже*/
    #[Route('/editSalesAutoParts', name: 'edit_sales_auto_parts')]
    public function editSalesAutoParts(
        Request $request,
        FindOneBySalesAutoPartsQueryHandler $findOneBySalesAutoPartsQueryHandler,
        EditSalesAutoPartsCommandHandler $editSalesAutoPartsCommandHandler
    ): Response {

        /*Подключаем формы*/
        $form_edit_sales_auto_parts = $this->createForm(EditSalesAutoPartsType::class);

        /*Валидация формы */
        $form_edit_sales_auto_parts->handleRequest($request);

        $valid_form_edit = [];
        if (!empty($request->request->all())) {

            $valid_form_edit = $request->request->all()['edit_sales_auto_parts'];
        }

        if ($form_edit_sales_auto_parts->isSubmitted()) {
            if ($form_edit_sales_auto_parts->isValid()) {
                try {

                    $editSalesAutoPartsCommandHandler
                        ->handler(new AutoPartsSoldCommand($form_edit_sales_auto_parts->getData()));
                    $this->addFlash('successfully', 'Успешное изменение данных');

                    return $this->redirectToRoute('search_sales');
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        try {

            $data_form_edit_sales_auto_parts = $findOneBySalesAutoPartsQueryHandler
                ->handler(new AutoPartsSoldQuery($request->query->all()));
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);

            return $this->redirectToRoute('search_sales');
        }

        return $this->render('@sales/editSalesAutoParts.html.twig', [
            'title_logo' => 'Изменение данных проданных деталей',
            'form_edit_sales_auto_parts' => $form_edit_sales_auto_parts->createView(),
            'data_form_edit_sales_auto_parts' => $data_form_edit_sales_auto_parts,
            'valid_form_edit' => $valid_form_edit
        ]);
    }

    /*Удаление продажи*/
    #[Route('/deleteSalesAutoParts', name: 'delete_sales_auto_parts')]
    public function deleteSalesAutoParts(
        Request $request,
        DeleteSalesAutoPartsCommandHandler $deleteSalesAutoPartsCommandHandler
    ): Response {

        try {

            $deleteSalesAutoPartsCommandHandler
                ->handler(new AutoPartsSoldCommand($request->query->all()));
            $this->addFlash('delete', 'Продажа удалена');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('search_sales');
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

    private function sumCartAutoPartsSales($cartAutoParts): int
    {
        $sum = 0;
        if (!empty($cartAutoParts)) {

            foreach ($cartAutoParts as $key => $value) {

                $sum += ($value->getPriceSold());
            }
        }

        return $sum;
    }
}
