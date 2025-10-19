<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAxles\EditAxlesType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAxles\SaveAxlesType;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAxles\SearchAxlesType;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery\AxlesQuery;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DeleteAxlesQuery\FindAxlesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\SearchAxlesQuery\FindByAxlesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\SearchAxlesQuery\SearchAxlesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesCommand\AxlesCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\EditAxlesCommand\EditAxlesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\SaveAxlesCommand\SaveAxlesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\EditAxlesQuery\FindOneByIdAxlesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DeleteAxlesCommand\DeleteAxlesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesObjCommand\AxlesObjCommand;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AxlesController extends AbstractController
{
    /*Сохранения Оси авто*/
    #[Route('saveAxle', name: 'save_axle')]
    public function saveAxle(
        Request $request,
        SaveAxlesCommandHandler $saveAxlesCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_axles = $this->createForm(SaveAxlesType::class);

        /*Валидация формы */
        $form_save_axles->handleRequest($request);

        $id = null;
        if ($form_save_axles->isSubmitted()) {
            if ($form_save_axles->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $axles = $this->mapAxlesParticipant($form_save_axles->getData(), $participant);
                    $id = $saveAxlesCommandHandler
                        ->handler(new AxlesCommand($axles));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@axles/saveAxle.html.twig', [
            'title_logo' => 'Добавление Оси авто',
            'form_save_axles' => $form_save_axles->createView(),
            'id' => $id
        ]);
    }

    /*Поиск Оси авто*/
    #[Route('searchAxle', name: 'search_axle')]
    public function searchAxle(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindByAxlesQueryHandler $findByAxlesQueryHandler,
        SearchAxlesQueryHandler $searchAxlesQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_axles = $this->createForm(SearchAxlesType::class);

        /*Валидация формы */
        $form_search_axles->handleRequest($request);

        try {
            $axles = new Axles;
            $participant = $adapterUserExtractionInterface->userExtraction();
            $axles = $this->mapObjectAxles($axles, $participant);
            $search_data = $findByAxlesQueryHandler->handler(new AxlesQuery($axles));
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        if ($form_search_axles->isSubmitted()) {
            if ($form_search_axles->isValid()) {

                try {
                    $axles = $this->mapAxlesParticipant($form_search_axles->getData(), $participant);
                    $search_data = $searchAxlesQueryHandler
                        ->handler(new AxlesQuery($axles));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@axles/searchAxle.html.twig', [
            'title_logo' => 'Поиск Оси авто',
            'form_search_axles' => $form_search_axles->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования Оси авто*/
    #[Route('editAxle', name: 'edit_axle')]
    public function editAxle(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByIdAxlesQueryHandler $findOneByIdAxlesQueryHandler,
        EditAxlesCommandHandler $editAxlesCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_axles = $this->createForm(EditAxlesType::class);

        /*Валидация формы */
        $form_edit_axles->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_axle');
        }

        if (empty($form_edit_axles->getData())) {

            $axles = $this->mapAxles($request->query->all()['id'], '', $participant);
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
        if ($form_edit_axles->isSubmitted()) {
            if ($form_edit_axles->isValid()) {

                $data_form_edit_axles = $request->request->all()['edit_axles'];
                $data_edit_axles = $this->mapAxles(
                    $form_edit_axles->getData()['id'],
                    $form_edit_axles->getData()['axle'],
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
            'form_edit_axles' => $form_edit_axles->createView(),
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
