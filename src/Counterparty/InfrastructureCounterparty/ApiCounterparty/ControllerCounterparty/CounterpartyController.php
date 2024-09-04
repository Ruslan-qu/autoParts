<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\ControllerCounterparty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CreateCounterpartyQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\EditCounterpartyType;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SaveCounterpartyType;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CreateCounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SearchCounterpartyType;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\CreateSearchCounterpartyQuery;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\CreateSaveCounterpartyCommand;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\EditCounterpartyQuery\CreateFindIdCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\CreateSearchCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\EditCounterpartyCommand\CreateEditCounterpartyCommandHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\CreateSaveCounterpartyCommandHandler;

class CounterpartyController extends AbstractController
{
    /*Сохранения постовщика*/
    #[Route('/saveCounterparty', name: 'save_counterparty')]
    public function saveCounterparty(
        Request $request,
        CreateSaveCounterpartyCommandHandler $createSaveCounterpartyCommandHandler
    ): Response {

        /*Форма сохранения постовщка*/
        $form_save_counterparty = $this->createForm(SaveCounterpartyType::class);

        /*Валидация формы */
        $form_save_counterparty->handleRequest($request);

        $arr_saving_information = [];
        if ($form_save_counterparty->isSubmitted()) {
            if ($form_save_counterparty->isValid()) {

                $arr_saving_information = $createSaveCounterpartyCommandHandler
                    ->handler(new CreateCounterpartyCommand($request->request->all()['save_counterparty']));
            }
        }

        return $this->render('counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
            'arr_saving_information' => $arr_saving_information
        ]);
    }

    /*Поиск постовщика*/
    #[Route('/searchCounterparty', name: 'search_counterparty')]
    public function searchCounterparty(
        Request $request,
        CounterpartyRepositoryInterface $counterparty_repository_interface,
        CreateSearchCounterpartyQueryHandler $createSearchCounterpartyQueryHandler
    ): Response {

        /*Форма поиска постовщка*/
        $form_search_counterparty = $this->createForm(SearchCounterpartyType::class);

        /*Валидация формы */
        $form_search_counterparty->handleRequest($request);

        /*Выводим полный список поставщиков*/
        $search_data = $counterparty_repository_interface->findAllCounterparty();

        if ($form_search_counterparty->isSubmitted()) {
            if ($form_search_counterparty->isValid()) {
                unset($search_data);
                $search_data = $createSearchCounterpartyQueryHandler
                    ->handler(new CreateCounterpartyQuery($request->request->all()['search_counterparty']));
            }
        }

        return $this->render('counterparty/searchCounterparty.html.twig', [
            'title_logo' => 'Поиск поставщика',
            'form_search_counterparty' => $form_search_counterparty->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования постовщика*/
    #[Route('/editCounterparty', name: 'edit_counterparty')]
    public function editCounterparty(
        Request $request,
        CreateFindIdCounterpartyQueryHandler $createFindIdCounterpartyQueryHandler,
        CreateEditCounterpartyCommandHandler $createEditCounterpartyCommandHandler
    ): Response {
        //dd($request->query->all());
        /*Форма Редактирования постовщка*/
        $form_edit_counterparty = $this->createForm(EditCounterpartyType::class);

        /*Валидация формы */
        $form_edit_counterparty->handleRequest($request);

        if (!empty($request->query->all())) {

            $find_id_edit_counterparty = $createFindIdCounterpartyQueryHandler
                ->handler(new CreateCounterpartyQuery($request->query->all()));
            if (empty($find_id_edit_counterparty)) {
                $this->addFlash('data_edit_counterparty', 'Поставщик не найден');

                return $this->redirectToRoute('search_counterparty');
            }
        }
        //dd($find_id_edit_counterparty);
        $data_form_edit_counterparty = [];
        $arr_saving_information = [];
        if ($form_edit_counterparty->isSubmitted()) {
            if ($form_edit_counterparty->isValid()) {

                //unset($find_id_edit_counterparty);
                $data_form_edit_counterparty = $request->request->all()['edit_counterparty'];
                $arr_saving_information = $createEditCounterpartyCommandHandler
                    ->handler(new CreateCounterpartyCommand($request->request->all()['edit_counterparty']));
            }
        }


        return $this->render('counterparty/editCounterparty.html.twig', [
            'title_logo' => 'Изменение данных поставщика',
            'form_edit_counterparty' => $form_edit_counterparty->createView(),
            'arr_saving_information' => $arr_saving_information,
            'find_id_edit_counterparty' => $find_id_edit_counterparty,
            'data_form_edit_counterparty' => $data_form_edit_counterparty,
        ]);
    }
}
