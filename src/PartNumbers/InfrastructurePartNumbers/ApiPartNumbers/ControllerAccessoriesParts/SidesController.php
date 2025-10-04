<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormSides\EditSidesType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormSides\SaveSidesType;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormSides\SearchSidesType;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery\SidesQuery;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\DeleteSidesQuery\FindSidesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\SearchSidesQuery\FindBySidesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\SearchSidesQuery\SearchSidesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesCommand\SidesCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\EditSidesCommand\EditSidesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\SaveSidesCommand\SaveSidesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\EditSidesQuery\FindOneByIdSidesQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DeleteSidesCommand\DeleteSidesCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesObjCommand\SidesObjCommand;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class SidesController extends AbstractController
{
    /*Сохранения сторону авто*/
    #[Route('/saveSide', name: 'save_side')]
    public function saveSide(
        Request $request,
        SaveSidesCommandHandler $saveSidesCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_sides = $this->createForm(SaveSidesType::class);

        /*Валидация формы */
        $form_save_sides->handleRequest($request);

        $id = null;
        if ($form_save_sides->isSubmitted()) {
            if ($form_save_sides->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $sides = $this->mapSidesParticipant($form_save_sides->getData(), $participant);
                    $id = $saveSidesCommandHandler
                        ->handler(new SidesCommand($sides));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@sides/saveSide.html.twig', [
            'title_logo' => 'Добавление стороны авто',
            'form_save_sides' => $form_save_sides->createView(),
            'id' => $id
        ]);
    }

    /*Поиск стороны авто*/
    #[Route('searchSide', name: 'search_side')]
    public function searchSide(
        Request $request,
        Sides $sides,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindBySidesQueryHandler $findBySidesQueryHandler,
        SearchSidesQueryHandler $searchSidesQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_sides = $this->createForm(SearchSidesType::class);

        /*Валидация формы */
        $form_search_sides->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $sides = $this->mapObjectSides($sides, $participant);
            $search_data = $findBySidesQueryHandler->handler(new SidesQuery($sides));
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
