<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartName\SavePartNameType;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartName\SearchPartNameType;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\PartNameQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery\FindByPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery\SearchPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\UserExtractionQuery\UserExtractionQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery\FindAllPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand\PartNameCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\SavePartNameCommand\SavePartNameCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DeletePartNameCommand\DeletePartNameCommandHandler;

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
                    $part_name = $this->mapPartName($form_save_part_name->getData(), $participant);
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
                    $part_name = $this->mapPartName($form_search_part_name->getData(), $participant);
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

    /*Удаление название автодетали*/
    #[Route('deletePartName', name: 'delete_part_name')]
    public function deletePartNumbers(
        Request $request,
        //  FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        // AdapterPartNumbersInterface $adapterPartNumbersInterface,
        DeletePartNameCommandHandler $deletePartNameCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {
            dd($request);
            // $data_part_numbers['id_details'] = $findIdPartNumbersQueryHandler
            //    ->handler(new PartNumbersQuery($request->query->all()));

            // $adapterPartNumbersInterface->AutoPartsWarehouseDeletePartNumbers($data_part_numbers);

            $deletePartNameCommandHandler
                ->handler(new PartNameCommand($request->query->all()));
            $this->addFlash('delete', 'Автодеталь удалена');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_part_numbers');
    }

    private function mapPartName(array $part_name, object $participant): array
    {
        $part_name['id_participant'] = $participant;

        return $part_name;
    }

    private function mapObjectPartName(PartName $part_name, object $participant): array
    {
        $arr_part_name['id'] = $part_name->getId();
        $arr_part_name['part_name'] = $part_name->getPartName();
        $arr_part_name['id_participant'] = $participant;

        return $arr_part_name;
    }
}
