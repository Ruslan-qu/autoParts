<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;

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

        if ($form_search_sides->isSubmitted()) {
            if ($form_search_sides->isValid()) {

                try {
                    $sides = $this->mapSidesParticipant($form_search_sides->getData(), $participant);

                    $search_data = $searchSidesQueryHandler
                        ->handler(new SidesQuery($sides));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@sides/searchSide.html.twig', [
            'title_logo' => 'Поиск стороны авто',
            'form_search_sides' => $form_search_sides->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования сторона авто*/
    #[Route('editSide', name: 'edit_side')]
    public function editSide(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByIdSidesQueryHandler $findOneByIdSidesQueryHandler,
        EditSidesCommandHandler $editSidesCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_sides = $this->createForm(EditSidesType::class);

        /*Валидация формы */
        $form_edit_sides->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_side');
        }

        if (empty($form_edit_sides->getData())) {

            $sides = $this->mapCarBrands($request->query->all()['id'], '', $participant);
            try {

                $data_form_edit_sides = $findOneByIdSidesQueryHandler
                    ->handler(new SidesQuery($sides));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_side');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_sides = $request->request->all()['edit_sides'];
        }

        $id = null;
        if ($form_edit_sides->isSubmitted()) {
            if ($form_edit_sides->isValid()) {

                $data_form_edit_sides = $request->request->all()['edit_sides'];
                $data_edit_sides = $this->mapCarBrands(
                    $form_edit_sides->getData()['id'],
                    $form_edit_sides->getData()['side'],
                    $participant
                );

                try {

                    $id = $editSidesCommandHandler
                        ->handler(new SidesCommand($data_edit_sides));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@sides/editSide.html.twig', [
            'title_logo' => 'Изменение марки авто',
            'form_edit_sides' => $form_edit_sides->createView(),
            'id' => $id,
            'data_form_edit_sides' => $data_form_edit_sides
        ]);
    }

    /*Удаление стороны авто*/
    #[Route('deleteSide', name: 'delete_side')]
    public function deleteSide(
        Request $request,
        FindSidesQueryHandler $findSidesQueryHandler,
        DeleteSidesCommandHandler $deleteSidesCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $sides = $findSidesQueryHandler
                ->handler(new SidesQuery($request->query->all()));

            $deleteSidesCommandHandler
                ->handler(new SidesObjCommand($sides));
            $this->addFlash('delete', 'сторона авто удалена');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_side');
    }

    private function mapSidesParticipant(array $sides, Participant $participant): array
    {
        $sides['id_participant'] = $participant;

        return $sides;
    }

    private function mapObjectSides(Sides $sides, Participant $participant): array
    {
        $arr_sides['id'] = $sides->getId();
        $arr_sides['side'] = $sides->getSide();
        $arr_sides['id_participant'] = $participant;

        return $arr_sides;
    }

    private function mapCarBrands($id = null, $side = null, $participant = null): array
    {
        $arr_sides['id'] = $id;
        $arr_sides['side'] = $side;
        $arr_sides['id_participant'] = $participant;

        return $arr_sides;
    }
}
