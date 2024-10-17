<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\ControllerAutoPartsWarehouse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsManuallyType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\EditAutoPartsWarehouseType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SearchAutoPartsWarehouseType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditAutoPartsWarehouseQuery\FindIdAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery\SearchAutoPartsWarehouseQueryHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\EditAutoPartsWarehouseCommand\EditAutoPartsWarehouseCommandHandler;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand\SaveAutoPartsWarehouseCommandHandler;

class AutoPartsWarehouseController extends AbstractController
{
    /*функция сохранения в ручную входящих автодеталей */
    #[Route('/saveAutoPartsManually', name: 'save_auto_parts_manually')]
    public function saveAutoPartsManually(
        Request $request,
        SaveAutoPartsWarehouseCommandHandler $saveAutoPartsWarehouseCommandHandler,
        AdapterAutoPartsWarehouseInterface $adapterAutoPartsWarehouseInterface
    ): Response {


        /*Подключаем формы */
        $form_save_auto_parts_manually = $this->createForm(SaveAutoPartsManuallyType::class);

        /*Валидация формы */
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
        SearchAutoPartsWarehouseQueryHandler $searchAutoPartsWarehouseQueryHandler
    ): Response {

        /*Форма поиска*/
        $form_search_auto_parts_warehouse = $this->createForm(SearchAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_search_auto_parts_warehouse->handleRequest($request);

        $search_data = [];
        if ($form_search_auto_parts_warehouse->isSubmitted()) {
            if ($form_search_auto_parts_warehouse->isValid()) {

                $search_data[] = $searchAutoPartsWarehouseQueryHandler
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
        FindIdAutoPartsWarehouseQueryHandler $findIdAutoPartsWarehouseQueryHandler,
        AdapterAutoPartsWarehouseInterface $adapterAutoPartsWarehouseInterface,
        EditAutoPartsWarehouseCommandHandler $editAutoPartsWarehouseCommandHandler,
        /* CreateEditOriginalRoomsCommandHandler $createEditOriginalRoomsCommandHandler,
        CreateSaveOriginalRoomsCommandHandler $createSaveOriginalRoomsCommandHandler,*/
    ): Response {

        /*Форма Редактирования постовщка*/
        $form_edit_auto_parts_warehouse = $this->createForm(EditAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_edit_auto_parts_warehouse->handleRequest($request);

        if (empty($form_edit_auto_parts_warehouse->getData())) {

            $data_form_edit_auto_parts_warehouse = $findIdAutoPartsWarehouseQueryHandler
                ->handler(new AutoPartsWarehouseQuery($request->query->all()));
            if (empty($data_form_edit_auto_parts_warehouse)) {
                $this->addFlash('not_found', 'Автодеталь на складе не найдена');

                return $this->redirectToRoute('search_auto_parts_warehouse');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_auto_parts_warehouse = $request->request->all()['edit_auto_parts_warehouse'];
        }


        $arr_saving_information = [];
        if ($form_edit_auto_parts_warehouse->isSubmitted()) {
            if ($form_edit_auto_parts_warehouse->isValid()) {
                $data_form_edit_auto_parts_warehouse = $request->request->all()['edit_auto_parts_warehouse'];

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


        return $this->render('autoPartsWarehouse/editAutoPartsManually.html.twig', [
            'title_logo' => 'Изменение данных склада',
            'form_edit_auto_parts_warehouse' => $form_edit_auto_parts_warehouse->createView(),
            'arr_saving_information' => $arr_saving_information,
            'data_form_edit_auto_parts_warehouse' => $data_form_edit_auto_parts_warehouse
        ]);
    }
}
