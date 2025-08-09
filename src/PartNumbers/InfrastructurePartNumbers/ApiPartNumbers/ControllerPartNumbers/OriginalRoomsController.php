<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerPartNumbers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\DomainPartNumbers\Factory\FactorySaveOriginalRooms;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormOriginalRooms\EditOriginalRoomsType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormOriginalRooms\SaveOriginalRoomsType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormOriginalRooms\SearchOriginalRoomsType;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand\SaveOriginalRoomsCommandHandler;

class OriginalRoomsController extends AbstractController
{
    /*Сохранения оригиналного номера детали*/
    #[Route('/saveOriginalNumber', name: 'save_original_number')]
    public function saveOriginalNumber(
        Request $request,
        SaveOriginalRoomsCommandHandler $saveOriginalRoomsCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_original_rooms = $this->createForm(SaveOriginalRoomsType::class);

        /*Валидация формы */
        $form_save_original_rooms->handleRequest($request);

        $id = null;
        if ($form_save_original_rooms->isSubmitted()) {
            if ($form_save_original_rooms->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $original_rooms = $this->mapOriginalRooms(
                        null,
                        $form_save_original_rooms->getData()['original_number'],
                        $form_save_original_rooms->getData()['replacing_original_number'],
                        $form_save_original_rooms->getData()['original_manufacturer'],
                        $participant
                    );


                    $id = $saveOriginalRoomsCommandHandler
                        ->handler(new OriginalRoomsCommand($original_rooms));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@originalRooms/saveOriginalNumber.html.twig', [
            'title_logo' => 'Добавление оригиналного номера',
            'form_save_original_rooms' => $form_save_original_rooms->createView(),
            'id' => $id
        ]);
    }

    /*Поиск оригиналного номера детали*/
    #[Route('searchOriginalNumber', name: 'search_original_number')]
    public function searchOriginalNumber(
        Request $request,
        OriginalRooms $originalRooms,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        // FindByOriginalRoomsQueryHandler $findByOriginalRoomsQueryHandler,
        // SearchOriginalRoomsQueryHandler $searchOriginalRoomsQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_original_rooms = $this->createForm(SearchOriginalRoomsType::class);

        /*Валидация формы */
        $form_search_original_rooms->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $original_rooms = $this->mapObjectOriginalRooms($originalRooms, $participant);
            $search_data = $findByOriginalRoomsQueryHandler->handler(new OriginalRoomsQuery($original_rooms));
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        if ($form_search_original_rooms->isSubmitted()) {
            if ($form_search_original_rooms->isValid()) {

                try {
                    $original_rooms = $this->mapOriginalRoomsParticipant($form_search_original_rooms->getData(), $participant);
                    $search_data = $searchOriginalRoomsQueryHandler
                        ->handler(new OriginalRoomsQuery($original_rooms));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@originalRooms/searchOriginalNumber.html.twig', [
            'title_logo' => 'Поиск оригиналного номера детали',
            'form_search_original_rooms' => $form_search_original_rooms->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования оригиналного номера детали*/
    #[Route('editOriginalNumber', name: 'edit_original_number')]
    public function editOriginalNumber(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindOneByIdOriginalRoomsQueryHandler $findOneByIdOriginalRoomsQueryHandler,
        //EditOriginalRoomsCommandHandler $editOriginalRoomsCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_original_rooms = $this->createForm(EditOriginalRoomsType::class);

        /*Валидация формы */
        $form_edit_original_rooms->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_original_number');
        }

        if (empty($form_edit_original_rooms->getData())) {

            $original_rooms = $this->mapOriginalRooms($request->query->all()['id'], '', $participant);
            try {

                $data_form_edit_original_rooms = $findOneByIdOriginalRoomsQueryHandler
                    ->handler(new OriginalRoomsQuery($original_rooms));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_original_number');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_original_rooms = $request->request->all()['edit_original_rooms'];
        }

        $id = null;
        if ($form_edit_original_rooms->isSubmitted()) {
            if ($form_edit_original_rooms->isValid()) {

                $data_form_edit_original_rooms = $request->request->all()['edit_original_rooms'];
                $data_edit_original_rooms = $this->mapOriginalRooms(
                    $form_edit_original_rooms->getData()['id'],
                    $form_edit_original_rooms->getData()['original_number'],
                    $participant
                );

                try {

                    $id = $editOriginalRoomsCommandHandler
                        ->handler(new OriginalRoomsCommand($data_edit_original_rooms));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@originalRooms/editOriginalNumber.html.twig', [
            'title_logo' => 'Изменение оригиналного номера детали',
            'form_edit_original_rooms' => $form_edit_original_rooms->createView(),
            'id' => $id,
            'data_form_edit_original_rooms' => $data_form_edit_original_rooms
        ]);
    }

    /*Удаление оригиналного номера детали*/
    #[Route('deleteOriginalNumber', name: 'delete_original_number')]
    public function deleteOriginalNumber(
        Request $request,
        //FindOriginalRoomsQueryHandler $findOriginalRoomsQueryHandler,
        //DeleteOriginalRoomsCommandHandler $deleteOriginalRoomsCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $original_rooms = $findOriginalRoomsQueryHandler
                ->handler(new OriginalRoomsQuery($request->query->all()));

            $deleteOriginalRoomsCommandHandler
                ->handler(new OriginalRoomsObjCommand($original_rooms));
            $this->addFlash('delete', 'Оригиналный номер детали удален');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_original_number');
    }

    private function mapOriginalRoomsParticipant(array $original_rooms, Participant $participant): array
    {
        $original_rooms['id_participant'] = $participant;

        return $original_rooms;
    }

    private function mapObjectOriginalRooms(OriginalRooms $original_rooms, Participant $participant): array
    {
        $arr_original_rooms['id'] = $original_rooms->getId();
        $arr_original_rooms['original_number'] = $original_rooms->getOriginalNumber();
        $arr_original_rooms['id_participant'] = $participant;

        return $arr_original_rooms;
    }

    private function mapOriginalRooms(
        $id,
        $original_number,
        $replacing_original_number,
        $original_manufacturer,
        $participant
    ): array {
        $arr_original_rooms['id'] = $id;
        $arr_original_rooms['original_number'] = $original_number;
        $arr_original_rooms['replacing_original_number'] = [$replacing_original_number];
        $arr_original_rooms['original_manufacturer'] = $original_manufacturer;
        $arr_original_rooms['id_participant'] = $participant;

        return $arr_original_rooms;
    }
}
