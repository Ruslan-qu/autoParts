<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\ControllerCounterparty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\EditCounterpartyType;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SaveCounterpartyType;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SearchCounterpartyType;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\EditCounterpartyQuery\FindIdCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindByCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\EditCounterpartyCommand\EditCounterpartyCommandHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\SaveCounterpartyCommandHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand\DeleteCounterpartyCommandHandler;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterCounterparty\AdapterCounterpartyInterface;

class CounterpartyController extends AbstractController
{
    /*Сохранения постовщика*/
    #[Route('/saveCounterparty', name: 'save_counterparty')]
    public function saveCounterparty(
        Request $request,
        SaveCounterpartyCommandHandler $saveCounterpartyCommandHandler
    ): Response {

        /*Форма сохранения постовщка*/
        $form_save_counterparty = $this->createForm(SaveCounterpartyType::class);

        /*Валидация формы */
        $form_save_counterparty->handleRequest($request);

        $id_handler = null;
        if ($form_save_counterparty->isSubmitted()) {
            if ($form_save_counterparty->isValid()) {

                try {

                    $id_handler = $saveCounterpartyCommandHandler
                        ->handler(new CounterpartyCommand($form_save_counterparty->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
            'id_handler' => $id_handler
        ]);
    }

    /*Поиск постовщика*/
    #[Route('/searchCounterparty', name: 'search_counterparty')]
    public function searchCounterparty(
        Request $request,
        FindByCounterpartyQueryHandler $findByCounterpartyQueryHandler,
        SearchCounterpartyQueryHandler $searchCounterpartyQueryHandler
    ): Response {

        /*Форма поиска постовщка*/
        $form_search_counterparty = $this->createForm(SearchCounterpartyType::class);

        /*Валидация формы */
        $form_search_counterparty->handleRequest($request);

        /*Выводим полный список поставщиков*/
        $search_data = $findByCounterpartyQueryHandler->handler();

        if ($form_search_counterparty->isSubmitted()) {
            if ($form_search_counterparty->isValid()) {

                try {

                    $search_data = $searchCounterpartyQueryHandler
                        ->handler(new CounterpartyQuery($form_search_counterparty->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@counterparty/searchCounterparty.html.twig', [
            'title_logo' => 'Поиск поставщика',
            'form_search_counterparty' => $form_search_counterparty->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования постовщика*/
    #[Route('/editCounterparty', name: 'edit_counterparty')]
    public function editCounterparty(
        Request $request,
        FindIdCounterpartyQueryHandler $findIdCounterpartyQueryHandler,
        EditCounterpartyCommandHandler $editCounterpartyCommandHandler
    ): Response {

        /*Форма Редактирования постовщка*/
        $form_edit_counterparty = $this->createForm(EditCounterpartyType::class);

        /*Валидация формы */
        $form_edit_counterparty->handleRequest($request);

        if (empty($form_edit_counterparty->getData())) {

            try {

                $data_form_edit_counterparty = $findIdCounterpartyQueryHandler
                    ->handler(new CounterpartyQuery($request->query->all()));
            } catch (HttpException $e) {

                $this->errorMessageViaSession($e);
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_counterparty = $request->request->all()['edit_counterparty'];
        }

        $id_handler = null;
        if ($form_edit_counterparty->isSubmitted()) {
            if ($form_edit_counterparty->isValid()) {

                $data_form_edit_counterparty = $request->request->all()['edit_counterparty'];
                try {

                    $id_handler = $editCounterpartyCommandHandler
                        ->handler(new CounterpartyCommand($form_edit_counterparty->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@counterparty/editCounterparty.html.twig', [
            'title_logo' => 'Изменение данных поставщика',
            'form_edit_counterparty' => $form_edit_counterparty->createView(),
            'id_handler' => $id_handler,
            'data_form_edit_counterparty' => $data_form_edit_counterparty,
        ]);
    }

    /*Удаление постовщика*/
    #[Route('/deleteCounterparty', name: 'delete_counterparty')]
    public function deleteCounterparty(
        Request $request,
        FindIdCounterpartyQueryHandler $findIdCounterpartyQueryHandler,
        AdapterCounterpartyInterface $adapterCounterpartyInterface,
        DeleteCounterpartyCommandHandler $deleteCounterpartyCommandHandler
    ): Response {

        try {

            $arr_counterparty['id_counterparty'] = $findIdCounterpartyQueryHandler
                ->handler(new CounterpartyQuery($request->query->all()));

            $adapterCounterpartyInterface->autoPartsWarehouseDeleteCounterparty($arr_counterparty);

            $deleteCounterpartyCommandHandler
                ->handler(new CounterpartyCommand($request->query->all()));
            $this->addFlash('delete', 'Поставщик удален');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('search_counterparty');
    }


    private function errorMessageViaSession(HttpException $e): static
    {

        $arr_validator_errors = json_decode($e->getMessage(), true);

        /* Выводим сообщения ошибки в форму через сессии  */
        foreach ($arr_validator_errors as $key => $value_errors) {
            if (is_array($value_errors)) {
                foreach ($value_errors as $key => $value) {
                    $message = $value;
                    $propertyPath = $key;
                }
            } else {
                $message = $value_errors;
                $propertyPath = $key;
            }

            $this->addFlash($propertyPath, $message);
        }

        return $this;
    }
}
