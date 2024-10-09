<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\ControllerAutoPartsWarehouse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SaveAutoPartsManuallyType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\SearchAutoPartsWarehouseType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;
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

                // dd($form_save_auto_parts_manually->getData());
                $map_arr_part_number_manufactur = [
                    'id_details' => $form_save_auto_parts_manually->getData()['id_details'],
                    'id_manufacturer' => $form_save_auto_parts_manually->getData()['id_manufacturer']
                ];

                $arr_part_number = $adapterAutoPartsWarehouseInterface
                    ->searchPartNumbersManufacturer($map_arr_part_number_manufactur);
                $map_arr_part_number_manufactur = [
                    'id_details' => $arr_part_number[0],
                    'id_manufacturer' => $arr_part_number[0]
                ];

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

    /*Поиск автодеталей*/
    #[Route('/searchAutoPartsWarehouse', name: 'search_auto_parts_warehouse')]
    public function searchAutoPartsWarehouse(
        Request $request,
        //CreateSearchPartNumbersQueryHandler $createSearchPartNumbersQueryHandler,
        //CreateFindOneByOriginalRoomsQueryHandler $createFindOneByOriginalRoomsQueryHandler,
    ): Response {

        /*Форма поиска*/
        $form_search_auto_parts_warehouse = $this->createForm(SearchAutoPartsWarehouseType::class);

        /*Валидация формы */
        $form_search_auto_parts_warehouse->handleRequest($request);

        $search_data = [];
        if ($form_search_auto_parts_warehouse->isSubmitted()) {
            if ($form_search_auto_parts_warehouse->isValid()) {

                $data_form_part_numbers = $form_search_part_numbers->getData();
                if (!empty($data_form_part_numbers['id_original_number'])) {

                    $arr_original_number['original_number'] = $data_form_part_numbers['id_original_number'];
                    $object_original_number = $createFindOneByOriginalRoomsQueryHandler
                        ->handler(new CreateOriginalRoomsQuery($arr_original_number));

                    $data_form_part_numbers = array_replace($data_form_part_numbers, $object_original_number);
                }

                $search_data[] = $createSearchPartNumbersQueryHandler
                    ->handler(new CreatePartNumbersQuery($data_form_part_numbers));
            }
        }

        return $this->render('autoPartsWarehouse/searchAutoPartsWarehouse.html.twig', [
            'title_logo' => 'Поиск автодетали на сладе',
            'form_search_auto_parts_warehouse' => $form_search_auto_parts_warehouse->createView(),
            'search_data' => $search_data,

        ]);
    }
}
