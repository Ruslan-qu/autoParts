<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\ControllerCounterparty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SaveCounterpartyType;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SearchCounterpartyType;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\CreateSaveCounterpartyCommand;
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
                    ->handler(new CreateSaveCounterpartyCommand($request->request->all()['save_counterparty']));
            }
        }

        return $this->render('counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
            'arr_saving_information' => $arr_saving_information
        ]);
    }

    /*Поиск постовщика*/
    #[Route('/searchCounterparty', name: 'searchCounterparty')]
    public function searchCounterparty(
        Request $request,
        CounterpartyRepositoryInterface $counterparty_repository_interface
    ): Response {

        /*Форма поиска постовщка*/
        $form_search_counterparty = $this->createForm(SearchCounterpartyType::class);

        /*Валидация формы */
        $form_search_counterparty->handleRequest($request);

        $arr_search_information = $counterparty_repository_interface->findAllCounterparty();
        //dd($arr_search_information);
        /* if ($form_search_counterparty->isSubmitted()) {
            if ($form_search_counterparty->isValid()) {

                $arr_search_information = $createSaveCounterpartyCommandHandler
                    ->handler(new CreateSaveCounterpartyCommand($request->request->all()['save_counterparty']));
                foreach ($arr_search_information as $value_arr_information) {
                    foreach ($value_arr_information as $value_search_information) {
                        $search_information = $value_search_information;
                    }
                }
            }
        }*/

        return $this->render('counterparty/searchCounterparty.html.twig', [
            'title_logo' => 'Поиск поставщика',
            'form_search_counterparty' => $form_search_counterparty->createView(),
            'arr_search_information' => $arr_search_information
        ]);
    }
}
