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
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SearchPartNumbersType;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DeletePartNumbersQuery\FindPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\SearchPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\FindAllPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand\EditPartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\FindOneByIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand\DeletePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersObjCommand\PartNumbersObjCommand;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\SearchOriginalRoomsQuery\FindOneByOriginalRoomsPartNumbersQueryHandler;

class PartNumbersController extends AbstractController
{
    /*Сохранения автодеталей*/
    #[Route('savePartNumbers', name: 'save_part_numbers')]
    public function savePartNumbers(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByOriginalRoomsPartNumbersQueryHandler $findOneByOriginalRoomsPartNumbersQueryHandler,
        SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
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
                        $original_number = $findOneByOriginalRoomsPartNumbersQueryHandler
                            ->handler(new OriginalRoomsQuery($original_rooms));
                    }

                    $part_numbers = $this->mapPartNumbers(
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
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByOriginalRoomsPartNumbersQueryHandler $findOneByOriginalRoomsPartNumbersQueryHandler,
        //FindAllPartNumbersQueryHandler $findAllPartNumbersQueryHandler,
        SearchPartNumbersQueryHandler $searchPartNumbersQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_part_numbers = $this->createForm(SearchPartNumbersType::class);

        /*Валидация формы */
        $form_search_part_numbers->handleRequest($request);

        //$search_data = $findAllPartNumbersQueryHandler->handler();
        $search_data = '';
        if ($form_search_part_numbers->isSubmitted()) {
            if ($form_search_part_numbers->isValid()) {
                try {
                    $participant = $adapterUserExtractionInterface->userExtraction();

                    $original_number = null;
                    if ($form_search_part_numbers->getData()['original_number'] != null) {
                        $original_rooms = $this->mapOriginalRooms(
                            null,
                            $form_search_part_numbers->getData()['original_number'],
                            null,
                            $participant
                        );
                        $original_number = $findOneByOriginalRoomsPartNumbersQueryHandler
                            ->handler(new OriginalRoomsQuery($original_rooms));
                    }

                    $part_numbers = $this->mapPartNumbers(
                        null,
                        $form_search_part_numbers->getData()['part_number'],
                        $original_number,
                        $form_search_part_numbers->getData()['manufacturer'],
                        null,
                        $form_search_part_numbers->getData()['id_part_name'],
                        $form_search_part_numbers->getData()['id_car_brand'],
                        $form_search_part_numbers->getData()['id_side'],
                        $form_search_part_numbers->getData()['id_body'],
                        $form_search_part_numbers->getData()['id_axle'],
                        $form_search_part_numbers->getData()['id_in_stock'],
                        $participant
                    );
                    $search_data = $searchPartNumbersQueryHandler
                        ->handler(new PartNumbersQuery($part_numbers));
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
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByOriginalRoomsPartNumbersQueryHandler $findOneByOriginalRoomsPartNumbersQueryHandler,
        FindOneByIdPartNumbersQueryHandler $findOneByIdPartNumbersQueryHandler,
        EditPartNumbersCommandHandler $editPartNumbersCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_part_numbers = $this->createForm(EditPartNumbersType::class);

        /*Валидация формы */
        $form_edit_part_numbers->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_part_numbers');
        }

        if (empty($form_edit_part_numbers->getData())) {
            try {
                $part_numbers = $this->mapPartNumbers(
                    $request->query->all()['id'],
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $participant
                );
                $data_form_edit_part_numbers = $findOneByIdPartNumbersQueryHandler
                    ->handler(new PartNumbersQuery($part_numbers))[0];
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

                try {
                    $original_number = null;
                    if ($form_edit_part_numbers->getData()['original_number'] != null) {
                        $original_rooms = $this->mapOriginalRooms(
                            null,
                            $form_edit_part_numbers->getData()['original_number'],
                            null,
                            $participant
                        );
                        $original_number = $findOneByOriginalRoomsPartNumbersQueryHandler
                            ->handler(new OriginalRoomsQuery($original_rooms));
                    }

                    $part_numbers = $this->mapPartNumbers(
                        $form_edit_part_numbers->getData()['id'],
                        $form_edit_part_numbers->getData()['part_number'],
                        $original_number,
                        $form_edit_part_numbers->getData()['manufacturer'],
                        $form_edit_part_numbers->getData()['additional_descriptions'],
                        $form_edit_part_numbers->getData()['id_part_name'],
                        $form_edit_part_numbers->getData()['id_car_brand'],
                        $form_edit_part_numbers->getData()['id_side'],
                        $form_edit_part_numbers->getData()['id_body'],
                        $form_edit_part_numbers->getData()['id_axle'],
                        $form_edit_part_numbers->getData()['id_in_stock'],
                        $participant
                    );
                    $id = $editPartNumbersCommandHandler
                        ->handler(new PartNumbersCommand($part_numbers));
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
        FindPartNumbersQueryHandler $FindPartNumbersQueryHandler,
        DeletePartNumbersCommandHandler $deletePartNumbersCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $part_numbers['part_numbers'] = $FindPartNumbersQueryHandler
                ->handler(new PartNumbersQuery($request->query->all()));

            $deletePartNumbersCommandHandler
                ->handler(new PartNumbersObjCommand($part_numbers));
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

    private function mapPartNumbers(
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
