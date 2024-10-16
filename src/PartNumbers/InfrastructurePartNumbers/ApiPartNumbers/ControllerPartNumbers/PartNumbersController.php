<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerPartNumbers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\EditPartNumbersType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SavePartNumbersType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SearchPartNumbersType;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand\EditPartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery\CreateOriginalRoomsQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\CreateFindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateSearchPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand\CreateEditPartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\CreateSavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand\CreateEditOriginalRoomsCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\CreateSaveOriginalRoomsCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateFindOneByOriginalRoomsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand\CreateDeletePartNumbersCommandHandler;
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

                    $arr_original_number['original_number'] = $data_form_part_numbers['id_original_number'];

                    $createSaveOriginalRoomsCommandHandler
                        ->handler(new CreateOriginalRoomsCommand($arr_original_number));

                    $object_original_number = $createFindOneByOriginalRoomsQueryHandler
                        ->handler(new CreateOriginalRoomsQuery($arr_original_number));

                    $data_form_part_numbers = array_replace($data_form_part_numbers, $object_original_number);
                }

                $arr_saving_information['id'] = $createSavePartNumbersCommandHandler
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

                    $arr_original_number['original_number'] = $data_form_part_numbers['id_original_number'];
                    $object_original_number = $createFindOneByOriginalRoomsQueryHandler
                        ->handler(new CreateOriginalRoomsQuery($arr_original_number));

                    $data_form_part_numbers = array_replace($data_form_part_numbers, $object_original_number);
                }

                $search_data[] = $createSearchPartNumbersQueryHandler
                    ->handler(new CreatePartNumbersQuery($data_form_part_numbers));
            }
        }

        return $this->render('partNumbers/searchPartNumbers.html.twig', [
            'title_logo' => 'Поиск автодетали',
            'form_search_part_numbers' => $form_search_part_numbers->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования автодеталей*/
    #[Route('/editPartNumbers', name: 'edit_part_numbers')]
    public function editPartNumbers(
        Request $request,
        CreateFindIdPartNumbersQueryHandler $createFindIdPartNumbersQueryHandler,
        EditPartNumbersCommandHandler $editPartNumbersCommandHandler,
        CreateFindOneByOriginalRoomsQueryHandler $createFindOneByOriginalRoomsQueryHandler,
        CreateEditOriginalRoomsCommandHandler $createEditOriginalRoomsCommandHandler,
        CreateSaveOriginalRoomsCommandHandler $createSaveOriginalRoomsCommandHandler,
    ): Response {

        /*Форма Редактирования постовщка*/
        $form_edit_part_numbers = $this->createForm(EditPartNumbersType::class);

        /*Валидация формы */
        $form_edit_part_numbers->handleRequest($request);

        if (empty($form_edit_part_numbers->getData())) {

            $data_form_edit_part_numbers = $createFindIdPartNumbersQueryHandler
                ->handler(new CreatePartNumbersQuery($request->query->all()));
            if (empty($data_form_edit_part_numbers)) {
                $this->addFlash('data_part_numbers', 'Автодеталь не найден');

                return $this->redirectToRoute('search_part_numbers');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_part_numbers = $request->request->all()['edit_part_numbers'];
        }


        $arr_saving_information = [];
        if ($form_edit_part_numbers->isSubmitted()) {
            if ($form_edit_part_numbers->isValid()) {
                $data_form_edit_part_numbers = $request->request->all()['edit_part_numbers'];
                $data_edit_part_numbers = $form_edit_part_numbers->getData();

                if (!empty($data_edit_part_numbers['original_number'])) {

                    $arr_original_number['id'] = $data_edit_part_numbers['id_original_number'];
                    $arr_original_number['original_number'] = $data_edit_part_numbers['original_number'];

                    if (!empty($data_edit_part_numbers['id_original_number'])) {

                        $createEditOriginalRoomsCommandHandler
                            ->handler(new CreateOriginalRoomsCommand($arr_original_number));
                    } else {
                        $createSaveOriginalRoomsCommandHandler
                            ->handler(new CreateOriginalRoomsCommand($arr_original_number));
                    }

                    $object_original_number = $createFindOneByOriginalRoomsQueryHandler
                        ->handler(new CreateOriginalRoomsQuery($arr_original_number));

                    $data_edit_part_numbers = array_replace($data_edit_part_numbers, $object_original_number);
                }

                unset($data_edit_part_numbers['original_number']);
                $arr_saving_information = $editPartNumbersCommandHandler
                    ->handler(new CreatePartNumbersCommand($data_edit_part_numbers));
            }
        }

        return $this->render('partNumbers/editPartNumbers.html.twig', [
            'title_logo' => 'Изменение данных автодеталей',
            'form_edit_part_numbers' => $form_edit_part_numbers->createView(),
            'arr_saving_information' => $arr_saving_information,
            'data_form_edit_part_numbers' => $data_form_edit_part_numbers
        ]);
    }

    /*Удаление автодетали*/
    #[Route('/deletePartNumbers', name: 'delete_part_numbers')]
    public function deletePartNumbers(
        Request $request,
        CreateDeletePartNumbersCommandHandler $createDeletePartNumbersCommandHandler
    ): Response {

        $arr_saving_information = $createDeletePartNumbersCommandHandler
            ->handler(new CreatePartNumbersCommand($request->query->all()));

        if (empty($arr_saving_information)) {

            $this->addFlash('data_part_numbers', 'Данные деталей не найдены');
        } else {

            foreach ($arr_saving_information as $arrValue) {
                foreach ($arrValue as $value) {
                    $this->addFlash('data_part_numbers', $value);
                }
            }
        }



        return $this->redirectToRoute('search_part_numbers');
    }
}
