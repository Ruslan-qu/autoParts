<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;

class AvailabilityController extends AbstractController
{
    /*Сохранения наличие детали*/
    #[Route('/saveInStock', name: 'save_in_stock')]
    public function saveInStock(
        Request $request,
        //SaveAvailabilityCommandHandler $saveAvailabilityCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_availability = $this->createForm(SaveAvailabilityType::class);

        /*Валидация формы */
        $form_save_availability->handleRequest($request);

        $id = null;
        if ($form_save_availability->isSubmitted()) {
            if ($form_save_availability->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $availability = $this->mapAvailabilityParticipant($form_save_availability->getData(), $participant);
                    $id = $saveAvailabilityCommandHandler
                        ->handler(new AvailabilityCommand($availability));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@availability/saveInStock.html.twig', [
            'title_logo' => 'Добавление наличие детали',
            'form_save_availability' => $form_save_availability->createView(),
            'id' => $id
        ]);
    }

    /*Поиск наличие детали*/
    #[Route('searchInStock', name: 'search_in_stock')]
    public function searchInStock(
        Request $request,
        Availability $availability,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindByAvailabilityQueryHandler $findByAvailabilityQueryHandler,
        //SearchAvailabilityQueryHandler $searchAvailabilityQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_availability = $this->createForm(SearchAvailabilityType::class);

        /*Валидация формы */
        $form_search_availability->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $availability = $this->mapObjectAvailability($availability, $participant);
            $search_data = $findByAvailabilityQueryHandler->handler(new AvailabilityQuery($availability));
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        if ($form_search_availability->isSubmitted()) {
            if ($form_search_availability->isValid()) {

                try {
                    $availability = $this->mapAvailabilityParticipant($form_search_availability->getData(), $participant);
                    $search_data = $searchAvailabilityQueryHandler
                        ->handler(new AvailabilityQuery($availability));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@availability/searchInStock.html.twig', [
            'title_logo' => 'Поиск наличие детали',
            'form_search_availability' => $form_search_availability->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования наличие детали*/
    #[Route('editInStock', name: 'edit_in_stock')]
    public function editInStock(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindOneByIdAvailabilityQueryHandler $findOneByIdAvailabilityQueryHandler,
        //EditAvailabilityCommandHandler $editAvailabilityCommandHandler,
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

                $data_form_edit_axles = $findOneByIdAxlesQueryHandler
                    ->handler(new AxlesQuery($axles));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_axle');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_axles = $request->request->all()['edit_axles'];
        }

        $id = null;
        if ($form_edit_availability->isSubmitted()) {
            if ($form_edit_availability->isValid()) {

                $data_form_edit_axles = $request->request->all()['edit_axles'];
                $data_edit_axles = $this->mapAxles(
                    $form_edit_availability->getData()['id'],
                    $form_edit_availability->getData()['axle'],
                    $participant
                );

                try {

                    $id = $editAxlesCommandHandler
                        ->handler(new AxlesCommand($data_edit_axles));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@axles/editAxle.html.twig', [
            'title_logo' => 'Изменение Оси авто',
            'form_edit_availability' => $form_edit_availability->createView(),
            'id' => $id,
            'data_form_edit_axles' => $data_form_edit_axles
        ]);
    }

    /*Удаление Оси авто*/
    #[Route('deleteAxle', name: 'delete_axle')]
    public function deleteAxle(
        Request $request,
        FindAxlesQueryHandler $findAxlesQueryHandler,
        DeleteAxlesCommandHandler $deleteAxlesCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $axles = $findAxlesQueryHandler
                ->handler(new AxlesQuery($request->query->all()));

            $deleteAxlesCommandHandler
                ->handler(new AxlesObjCommand($axles));
            $this->addFlash('delete', 'Ось авто удалена');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_axle');
    }

    private function mapAxlesParticipant(array $axles, Participant $participant): array
    {
        $axles['id_participant'] = $participant;

        return $axles;
    }

    private function mapObjectAxles(Axles $axles, Participant $participant): array
    {
        $arr_axles['id'] = $axles->getId();
        $arr_axles['axle'] = $axles->getAxle();
        $arr_axles['id_participant'] = $participant;

        return $arr_axles;
    }

    private function mapAxles($id = null, $axle = null, $participant = null): array
    {
        $arr_axles['id'] = $id;
        $arr_axles['axle'] = $axle;
        $arr_axles['id_participant'] = $participant;

        return $arr_axles;
    }
}
