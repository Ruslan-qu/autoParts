<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerPartNumbers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SavePartNumbersType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SearchPartNumbersType;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery\CreateOriginalRoomsQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateSearchPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateSearchOriginalRoomsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\CreateSavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\CreateSaveOriginalRoomsCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateFindOneByOriginalRoomsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\CreateOriginalRoomsCommand;

class PartNumbersController extends AbstractController
{
    /*Сохранения автодеталей*/
    #[Route('/savePartNumbers', name: 'save_part_numbers')]
    public function savePartNumbers(
        Request $request,
        CreateSavePartNumbersCommandHandler $createSavePartNumbersCommandHandler,
        CreateSaveOriginalRoomsCommandHandler $createSaveOriginalRoomsCommandHandler,
        CreateFindOneByOriginalRoomsQueryHandler $createFindOneByOriginalRoomsQueryHandler,
    ): Response {

        /* Форма сохранения */
        $form_save_part_numbers = $this->createForm(SavePartNumbersType::class);

        /*Валидация формы */
        $form_save_part_numbers->handleRequest($request);

        $arr_saving_information = [];
        if ($form_save_part_numbers->isSubmitted()) {
            if ($form_save_part_numbers->isValid()) {

                $data_form_part_numbers = $form_save_part_numbers->getData();
                if (!empty($data_form_part_numbers['id_original_number'])) {

                    $createSaveOriginalRoomsCommandHandler
                        ->handler(new CreateOriginalRoomsCommand($data_form_part_numbers));

                    $object_original_number = $createFindOneByOriginalRoomsQueryHandler
                        ->handler(new CreateOriginalRoomsQuery($data_form_part_numbers));

                    $data_form_part_numbers = array_replace($data_form_part_numbers, $object_original_number);
                }

                $arr_saving_information = $createSavePartNumbersCommandHandler
                    ->handler(new CreatePartNumbersCommand($data_form_part_numbers));
            }
        }

        return $this->render('partNumbers/savePartNumbers.html.twig', [
            'title_logo' => 'Добавление новой автодетали',
            'form_save_part_numbers' => $form_save_part_numbers->createView(),
            'arr_saving_information' => $arr_saving_information
        ]);
    }

    /*Поиск автодеталей*/
    #[Route('/searchPartNumbers', name: 'search_part_numbers')]
    public function searchPartNumbers(
        Request $request,
        PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        CreateSearchPartNumbersQueryHandler $createSearchPartNumbersQueryHandler,
        CreateFindOneByOriginalRoomsQueryHandler $createFindOneByOriginalRoomsQueryHandler,
    ): Response {

        /*Форма поиска*/
        $form_search_part_numbers = $this->createForm(SearchPartNumbersType::class);

        /*Валидация формы */
        $form_search_part_numbers->handleRequest($request);

        $search_data = [];
        if ($form_search_part_numbers->isSubmitted()) {
            if ($form_search_part_numbers->isValid()) {

                $data_form_part_numbers = $form_search_part_numbers->getData();
                if (!empty($data_form_part_numbers['id_original_number'])) {

                    $object_original_number = $createFindOneByOriginalRoomsQueryHandler
                        ->handler(new CreateOriginalRoomsQuery($data_form_part_numbers));

                    $data_form_part_numbers = array_replace($data_form_part_numbers, $object_original_number);
                }

                $search_data = $createSearchPartNumbersQueryHandler
                    ->handler(new CreatePartNumbersQuery($data_form_part_numbers));
            }
        }

        return $this->render('partNumbers/searchPartNumbers.html.twig', [
            'title_logo' => 'Поиск автодетали',
            'form_search_part_numbers' => $form_search_part_numbers->createView(),
            'search_data' => $search_data,

        ]);
    }
}
