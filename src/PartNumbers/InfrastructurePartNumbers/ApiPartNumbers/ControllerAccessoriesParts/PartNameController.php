<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartName\SavePartNameType;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartName\SearchPartNameType;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\PartNameQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DeletePartNameQuery\FindPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery\FindByPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery\SearchPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\UserExtractionQuery\UserExtractionQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery\FindAllPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand\PartNameCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\SavePartNameCommand\SavePartNameCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DeletePartNameCommand\DeletePartNameCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameObjCommand\PartNameObjCommand;

class PartNameController extends AbstractController
{
    /*Сохранения название детали*/
    #[Route('/savePartName', name: 'save_part_name')]
    public function savePartName(
        Request $request,
        SavePartNameCommandHandler $savePartNameCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_part_name = $this->createForm(SavePartNameType::class);

        /*Валидация формы */
        $form_save_part_name->handleRequest($request);

        $id = null;
        if ($form_save_part_name->isSubmitted()) {
            if ($form_save_part_name->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $part_name = $this->mapPartNameParticipant($form_save_part_name->getData(), $participant);
                    $id = $savePartNameCommandHandler
                        ->handler(new PartNameCommand($part_name));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@partName/savePartName.html.twig', [
            'title_logo' => 'Добавление названия детали',
            'form_save_part_name' => $form_save_part_name->createView(),
            'id' => $id
        ]);
    }

    /*Поиск название детали*/
    #[Route('searchPartName', name: 'search_part_name')]
    public function searchPartName(
        Request $request,
        PartName $part_name,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindByPartNameQueryHandler $findByPartNameQueryHandler,
        SearchPartNameQueryHandler $searchPartNameQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_part_name = $this->createForm(SearchPartNameType::class);

        /*Валидация формы */
        $form_search_part_name->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $part_name = $this->mapObjectPartName($part_name, $participant);
            $search_data = $findByPartNameQueryHandler->handler(new PartNameQuery($part_name));
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        if ($form_search_part_name->isSubmitted()) {
            if ($form_search_part_name->isValid()) {

                try {
                    $part_name = $this->mapPartNameParticipant($form_search_part_name->getData(), $participant);
                    $search_data = $searchPartNameQueryHandler
                        ->handler(new PartNameQuery($part_name));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }
        //dd($search_data);
        return $this->render('@partName/searchPartName.html.twig', [
            'title_logo' => 'Поиск название детали',
            'form_search_part_name' => $form_search_part_name->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования автодеталей*/
    #[Route('editPartName', name: 'edit_part_name')]
    public function editPartNumbers(
        Request $request,
        //FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        // EditPartNumbersCommandHandler $editPartNumbersCommandHandler,
        // FindOneByOriginalRoomsQueryHandler $findOneByOriginalRoomsQueryHandler,
        // EditOriginalRoomsCommandHandler $editOriginalRoomsCommandHandler,
        // SaveOriginalRoomsCommandHandler $saveOriginalRoomsCommandHandler,
        // ErrorMessageViaSession $errorMessageViaSession
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

    /*Удаление название автодетали*/
    #[Route('deletePartName', name: 'delete_part_name')]
    public function deletePartNumbers(
        Request $request,
        FindPartNameQueryHandler $findPartNameQueryHandler,
        DeletePartNameCommandHandler $deletePartNameCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $part_name = $findPartNameQueryHandler
                ->handler(new PartNameQuery($request->query->all()));

            $deletePartNameCommandHandler
                ->handler(new PartNameObjCommand($part_name));
            $this->addFlash('delete', 'Автодеталь удалена');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_part_name');
    }

    private function mapPartNameParticipant(array $part_name, Participant $participant): array
    {
        $part_name['id_participant'] = $participant;

        return $part_name;
    }

    private function mapObjectPartName(PartName $part_name, Participant $participant): array
    {
        $arr_part_name['id'] = $part_name->getId();
        $arr_part_name['part_name'] = $part_name->getPartName();
        $arr_part_name['id_participant'] = $participant;

        return $arr_part_name;
    }

    /*private function mapPartName(PartName $part_name): array
    {
        $arr_part_name['id'] = $part_name->getId();
        $arr_part_name['part_name'] = $part_name->getPartName();
        $arr_part_name['id_participant'] = $part_name->getIdParticipant();

        return $arr_part_name;
    }*/
}
