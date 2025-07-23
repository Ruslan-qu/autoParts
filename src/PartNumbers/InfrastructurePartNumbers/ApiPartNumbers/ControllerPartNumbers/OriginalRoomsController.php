<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

class OriginalRoomsController extends AbstractController
{
    /*Сохранения оригиналного номера детали*/
    #[Route('/saveOriginalNumber', name: 'save_original_number')]
    public function saveOriginalNumber(
        Request $request,
        //SaveOriginalRoomsCommandHandler $saveOriginalRoomsCommandHandler,
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
                    $original_rooms = $this->mapOriginalRoomsParticipant($form_save_original_rooms->getData(), $participant);
                    $id = $saveOriginalRoomsCommandHandler
                        ->handler(new OriginalRoomsCommand($original_rooms));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@original_rooms/saveOriginalNumber.html.twig', [
            'title_logo' => 'Добавление оригиналного номера детали',
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

        return $this->render('@original_rooms/searchOriginalNumber.html.twig', [
            'title_logo' => 'Поиск наличие детали',
            'form_search_original_rooms' => $form_search_original_rooms->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования наличие детали*/
    #[Route('editInStock', name: 'edit_in_stock')]
    public function editInStock(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByIdAvailabilityQueryHandler $findOneByIdAvailabilityQueryHandler,
        EditAvailabilityCommandHandler $editAvailabilityCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_availability = $this->createForm(EditAvailabilityType::class);

        /*Валидация формы */
        $form_edit_availability->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_in_stock');
        }

        if (empty($form_edit_availability->getData())) {

            $availability = $this->mapAvailability($request->query->all()['id'], '', $participant);
            try {

                $data_form_edit_availability = $findOneByIdAvailabilityQueryHandler
                    ->handler(new AvailabilityQuery($availability));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_in_stock');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_availability = $request->request->all()['edit_availability'];
        }

        $id = null;
        if ($form_edit_availability->isSubmitted()) {
            if ($form_edit_availability->isValid()) {

                $data_form_edit_availability = $request->request->all()['edit_availability'];
                $data_edit_availability = $this->mapAvailability(
                    $form_edit_availability->getData()['id'],
                    $form_edit_availability->getData()['in_stock'],
                    $participant
                );

                try {

                    $id = $editAvailabilityCommandHandler
                        ->handler(new AvailabilityCommand($data_edit_availability));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@availability/editInStock.html.twig', [
            'title_logo' => 'Изменение наличие детали',
            'form_edit_availability' => $form_edit_availability->createView(),
            'id' => $id,
            'data_form_edit_availability' => $data_form_edit_availability
        ]);
    }

    /*Удаление наличие детали*/
    #[Route('deleteInStock', name: 'delete_in_stock')]
    public function deleteInStock(
        Request $request,
        FindAvailabilityQueryHandler $findAvailabilityQueryHandler,
        DeleteAvailabilityCommandHandler $deleteAvailabilityCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $availability = $findAvailabilityQueryHandler
                ->handler(new AvailabilityQuery($request->query->all()));

            $deleteAvailabilityCommandHandler
                ->handler(new AvailabilityObjCommand($availability));
            $this->addFlash('delete', 'Наличие детали удалено');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_in_stock');
    }

    private function mapAvailabilityParticipant(array $availability, Participant $participant): array
    {
        $availability['id_participant'] = $participant;

        return $availability;
    }

    private function mapObjectAvailability(Availability $availability, Participant $participant): array
    {
        $arr_availability['id'] = $availability->getId();
        $arr_availability['in_stock'] = $availability->getInStock();
        $arr_availability['id_participant'] = $participant;

        return $arr_availability;
    }

    private function mapAvailability($id = null, $in_stock = null, $participant = null): array
    {
        $arr_availability['id'] = $id;
        $arr_availability['in_stock'] = $in_stock;
        $arr_availability['id_participant'] = $participant;

        return $arr_availability;
    }
}
