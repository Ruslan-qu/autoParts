<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerPartNumbers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\EditPartNumbersType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SavePartNumbersType;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\AdaptersInterface\AdapterPartNumbersInterface;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SearchPartNumbersType;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\FindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\SearchPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\FindAllPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand\EditPartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SavePartNumbersNumbersQuery\FindOneByOriginalQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand\DeletePartNumbersCommandHandler;

class PartNumbersController extends AbstractController
{
    /*Сохранения автодеталей*/
    #[Route('savePartNumbers', name: 'save_part_numbers')]
    public function savePartNumbers(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        FindOneByOriginalQueryHandler $findOneByOriginalQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_part_numbers = $this->createForm(SavePartNumbersType::class);

        /*Валидация формы */
        $form_save_part_numbers->handleRequest($request);

        $id = null;
        if ($form_save_part_numbers->isSubmitted()) {
            if ($form_save_part_numbers->isValid()) {

                try {
                    $participant = $adapterUserExtractionInterface->userExtraction();

                    $original_number = null;
                    if ($form_save_part_numbers->getData()['original_number'] != null) {
                        $original_rooms = $this->mapOriginalRooms(
                            null,
                            $form_save_part_numbers->getData()['original_number'],
                            null,
                            $participant
                        );
                        $original_number = $findOneByOriginalQueryHandler
                            ->handler(new OriginalRoomsQuery($original_rooms));
                    }

                    $part_numbers = $this->mapePartNumbers(
                        null,
                        $form_save_part_numbers->getData()['part_number'],
                        $original_number,
                        $form_save_part_numbers->getData()['manufacturer'],
                        $form_save_part_numbers->getData()['additional_descriptions'],
                        $form_save_part_numbers->getData()['id_part_name'],
                        $form_save_part_numbers->getData()['id_car_brand'],
                        $form_save_part_numbers->getData()['id_side'],
                        $form_save_part_numbers->getData()['id_body'],
                        $form_save_part_numbers->getData()['id_axle'],
                        $form_save_part_numbers->getData()['id_in_stock'],
                        $participant
                    );
                    $id = $savePartNumbersCommandHandler
                        ->handler(new PartNumbersCommand($part_numbers));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@partNumbers/savePartNumbers.html.twig', [
            'title_logo' => 'Добавление новой автодетали',
            'form_save_part_numbers' => $form_save_part_numbers->createView(),
            'id' => $id
        ]);
    }

    /*Поиск автодеталей*/
    #[Route('searchPartNumbers', name: 'search_part_numbers')]
    public function searchPartNumbers(
        Request $request,
        FindAllPartNumbersQueryHandler $findAllPartNumbersQueryHandler,
        SearchPartNumbersQueryHandler $searchPartNumbersQueryHandler,
        //FindOneByOriginalRoomsQueryHandler $findOneByOriginalRoomsQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_part_numbers = $this->createForm(SearchPartNumbersType::class);

        /*Валидация формы */
        $form_search_part_numbers->handleRequest($request);

        $search_data = $findAllPartNumbersQueryHandler->handler();
        if ($form_search_part_numbers->isSubmitted()) {
            if ($form_search_part_numbers->isValid()) {
try {
                $original_number = null;
                if ($form_search_part_numbers->getData()['original_number'] != null) {

                    $arr_original_number['original_number'] = $data_form_part_numbers['id_original_number'];
                    

                        $original_number = $findOneByOriginalRoomsQueryHandler
                            ->handler(new OriginalRoomsQuery($arr_original_number));
                       
                    } catch (HttpException $e) {

                        $errorMessageViaSession->errorMessageSession($e);

                        return $this->redirectToRoute('search_part_numbers');
                    
                }
                try {

                    $search_data = $searchPartNumbersQueryHandler
                        ->handler(new PartNumbersQuery($data_form_part_numbers));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@partNumbers/searchPartNumbers.html.twig', [
            'title_logo' => 'Поиск автодетали',
            'form_search_part_numbers' => $form_search_part_numbers->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования автодеталей*/
    #[Route('editPartNumbers', name: 'edit_part_numbers')]
    public function editPartNumbers(
        Request $request,
        FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        EditPartNumbersCommandHandler $editPartNumbersCommandHandler,
        // FindOneByOriginalRoomsQueryHandler $findOneByOriginalRoomsQueryHandler,
        //EditOriginalRoomsCommandHandler $editOriginalRoomsCommandHandler,
        //SaveOriginalRoomsCommandHandler $saveOriginalRoomsCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_part_numbers = $this->createForm(EditPartNumbersType::class);

        /*Валидация формы */
        $form_edit_part_numbers->handleRequest($request);

        if (empty($form_edit_part_numbers->getData())) {
            try {

                $data_form_edit_part_numbers = $findIdPartNumbersQueryHandler
                    ->handler(new PartNumbersQuery($request->query->all()));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_part_numbers');
            }
        }

        if (!empty($request->request->all())) {

            $data_form_edit_part_numbers = $request->request->all()['edit_part_numbers'];
        }

        $id = null;
        if ($form_edit_part_numbers->isSubmitted()) {
            if ($form_edit_part_numbers->isValid()) {

                $data_form_edit_part_numbers = $request->request->all()['edit_part_numbers'];
                $data_edit_part_numbers = $form_edit_part_numbers->getData();
                if (!empty($data_edit_part_numbers['original_number'])) {

                    $arr_original_number['id'] = $data_edit_part_numbers['id_original_number'];
                    $arr_original_number['original_number'] = $data_edit_part_numbers['original_number'];

                    try {
                        if (!empty($data_edit_part_numbers['id_original_number'])) {

                            $editOriginalRoomsCommandHandler
                                ->handler(new OriginalRoomsCommand($arr_original_number));
                        } else {

                            $saveOriginalRoomsCommandHandler
                                ->handler(new OriginalRoomsCommand($arr_original_number));
                        }
                        $object_original_number = $findOneByOriginalRoomsQueryHandler
                            ->handler(new OriginalRoomsQuery($arr_original_number));
                        $data_edit_part_numbers = array_replace($data_edit_part_numbers, $object_original_number);
                    } catch (HttpException $e) {

                        $errorMessageViaSession->errorMessageSession($e);
                    }
                }

                unset($data_edit_part_numbers['original_number']);

                try {

                    $id = $editPartNumbersCommandHandler
                        ->handler(new PartNumbersCommand($data_edit_part_numbers));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@partNumbers/editPartNumbers.html.twig', [
            'title_logo' => 'Изменение данных автодеталей',
            'form_edit_part_numbers' => $form_edit_part_numbers->createView(),
            'id' => $id,
            'data_form_edit_part_numbers' => $data_form_edit_part_numbers
        ]);
    }

    /*Удаление автодетали*/
    #[Route('deletePartNumbers', name: 'delete_part_numbers')]
    public function deletePartNumbers(
        Request $request,
        FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        AdapterPartNumbersInterface $adapterPartNumbersInterface,
        DeletePartNumbersCommandHandler $deletePartNumbersCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $data_part_numbers['id_details'] = $findIdPartNumbersQueryHandler
                ->handler(new PartNumbersQuery($request->query->all()));

            $adapterPartNumbersInterface->AutoPartsWarehouseDeletePartNumbers($data_part_numbers);

            $deletePartNumbersCommandHandler
                ->handler(new PartNumbersCommand($request->query->all()));
            $this->addFlash('delete', 'Автодеталь удалена');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_part_numbers');
    }

    private function mapOriginalRooms(
        $id,
        $original_number,
        $original_manufacturer,
        $participant
    ): array {
        $arr_original_rooms['id'] = $id;
        $arr_original_rooms['original_number'] = $original_number;
        $arr_original_rooms['original_manufacturer'] = $original_manufacturer;
        $arr_original_rooms['id_participant'] = $participant;

        return $arr_original_rooms;
    }

    private function mapePartNumbers(
        $id,
        $part_number,
        $id_original_number,
        $manufacturer,
        $additional_descriptions,
        $id_part_name,
        $id_car_brand,
        $id_side,
        $id_body,
        $id_axle,
        $id_in_stock,
        $participant
    ): array {
        $arr_part_numbers['id'] = $id;
        $arr_part_numbers['part_number'] = $part_number;
        $arr_part_numbers['id_original_number'] = $id_original_number;
        $arr_part_numbers['manufacturer'] = $manufacturer;
        $arr_part_numbers['additional_descriptions'] = $additional_descriptions;
        $arr_part_numbers['id_part_name'] = $id_part_name;
        $arr_part_numbers['id_car_brand'] = $id_car_brand;
        $arr_part_numbers['id_side'] = $id_side;
        $arr_part_numbers['id_body'] = $id_body;
        $arr_part_numbers['id_axle'] = $id_axle;
        $arr_part_numbers['id_in_stock'] = $id_in_stock;
        $arr_part_numbers['id_participant'] = $participant;

        return $arr_part_numbers;
    }
}
