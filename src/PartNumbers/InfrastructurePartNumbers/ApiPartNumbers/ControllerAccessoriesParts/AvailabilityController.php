<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAvailability\EditAvailabilityType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAvailability\SaveAvailabilityType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAvailability\SearchAvailabilityType;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\AvailabilityQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DeleteAvailabilityQuery\FindAvailabilityQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\SearchAvailabilityQuery\FindByAvailabilityQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\SearchAvailabilityQuery\SearchAvailabilityQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand\AvailabilityCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\EditAvailabilityCommand\EditAvailabilityCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\SaveAvailabilityCommand\SaveAvailabilityCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\EditAvailabilityQuery\FindOneByIdAvailabilityQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DeleteAvailabilityCommand\DeleteAvailabilityCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityObjCommand\AvailabilityObjCommand;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AvailabilityController extends AbstractController
{
    /*Сохранения наличие детали*/
    #[Route('/saveInStock', name: 'save_in_stock')]
    public function saveInStock(
        Request $request,
        SaveAvailabilityCommandHandler $saveAvailabilityCommandHandler,
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
        FindByAvailabilityQueryHandler $findByAvailabilityQueryHandler,
        SearchAvailabilityQueryHandler $searchAvailabilityQueryHandler,
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
