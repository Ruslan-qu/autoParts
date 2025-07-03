<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormBodies\EditBodiesType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormBodies\SaveBodiesType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormBodies\SearchBodiesType;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DeleteBodiesCommand\DeleteBodiesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesObjCommand\BodiesObjCommand;

class BodiesController extends AbstractController
{
    /*Сохранения Кузов авто*/
    #[Route('/saveBody', name: 'save_body')]
    public function saveBody(
        Request $request,
        //SaveBodiesCommandHandler $saveBodiesCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_bodies = $this->createForm(SaveBodiesType::class);

        /*Валидация формы */
        $form_save_bodies->handleRequest($request);

        $id = null;
        if ($form_save_bodies->isSubmitted()) {
            if ($form_save_bodies->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $bodies = $this->mapBodiesParticipant($form_save_bodies->getData(), $participant);
                    $id = $saveBodiesCommandHandler
                        ->handler(new BodiesCommand($bodies));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@bodies/saveBody.html.twig', [
            'title_logo' => 'Добавление стороны авто',
            'form_save_bodies' => $form_save_bodies->createView(),
            'id' => $id
        ]);
    }

    /*Поиск Кузов авто*/
    #[Route('searchBody', name: 'search_body')]
    public function searchBody(
        Request $request,
        Bodies $bodies,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindByBodiesQueryHandler $findByBodiesQueryHandler,
        //SearchBodiesQueryHandler $searchBodiesQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_bodies = $this->createForm(SearchBodiesType::class);

        /*Валидация формы */
        $form_search_bodies->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $bodies = $this->mapObjectBodies($bodies, $participant);
            $search_data = $findByBodiesQueryHandler->handler(new BodiesQuery($bodies));
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        if ($form_search_bodies->isSubmitted()) {
            if ($form_search_bodies->isValid()) {

                try {
                    $sides = $this->mapSidesParticipant($form_search_bodies->getData(), $participant);

                    $search_data = $searchSidesQueryHandler
                        ->handler(new BodiesQuery($sides));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@bodies/searchBody.html.twig', [
            'title_logo' => 'Поиск кузов авто',
            'form_search_bodies' => $form_search_bodies->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования Кузов авто*/
    #[Route('editBody', name: 'edit_body')]
    public function editBody(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindOneByIdBodiesQueryHandler $findOneByIdBodiesQueryHandler,
        //EditBodiesCommandHandler $editBodiesCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_bodies = $this->createForm(EditBodiesType::class);

        /*Валидация формы */
        $form_edit_bodies->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_body');
        }

        if (empty($form_edit_bodies->getData())) {

            $bodies = $this->mapBodies($request->query->all()['id'], '', $participant);
            try {

                $data_form_edit_bodies = $findOneByIdBodiesQueryHandler
                    ->handler(new BodiesQuery($bodies));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_body');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_bodies = $request->request->all()['edit_bodies'];
        }

        $id = null;
        if ($form_edit_bodies->isSubmitted()) {
            if ($form_edit_bodies->isValid()) {

                $data_form_edit_bodies = $request->request->all()['edit_bodies'];
                $data_edit_Bodies = $this->mapBodies(
                    $form_edit_bodies->getData()['id'],
                    $form_edit_bodies->getData()['body'],
                    $participant
                );

                try {

                    $id = $editBodiesCommandHandler
                        ->handler(new BodiesCommand($data_edit_bodies));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@bodies/editBody.html.twig', [
            'title_logo' => 'Изменение кузов авто',
            'form_edit_bodies' => $form_edit_bodies->createView(),
            'id' => $id,
            'data_form_edit_bodies' => $data_form_edit_bodies
        ]);
    }

    /*Удаление кузова авто*/
    #[Route('deleteBody', name: 'delete_body')]
    public function deleteBody(
        Request $request,
        //FindBodiesQueryHandler $findBodiesQueryHandler,
        DeleteBodiesCommandHandler $deleteBodiesCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $bodies = $findBodiesQueryHandler
                ->handler(new BodiesQuery($request->query->all()));

            $deleteBodiesCommandHandler
                ->handler(new BodiesObjCommand($bodies));
            $this->addFlash('delete', 'Кузов авто удален');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_Bodн');
    }

    private function mapBodiesParticipant(array $bodies, Participant $participant): array
    {
        $bodies['id_participant'] = $participant;

        return $bodies;
    }

    private function mapObjectBodies(Bodies $bodies, Participant $participant): array
    {
        $arr_bodies['id'] = $bodies->getId();
        $arr_bodies['body'] = $bodies->getBody();
        $arr_bodies['id_participant'] = $participant;

        return $arr_bodies;
    }

    private function mapBodies($id = null, $body = null, $participant = null): array
    {
        $arr_bodies['id'] = $id;
        $arr_bodies['body'] = $body;
        $arr_bodies['id_participant'] = $participant;

        return $arr_bodies;
    }
}
