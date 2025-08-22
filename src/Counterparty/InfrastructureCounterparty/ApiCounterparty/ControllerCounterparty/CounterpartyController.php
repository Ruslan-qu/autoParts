<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\ControllerCounterparty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\EditCounterpartyType;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SaveCounterpartyType;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SearchCounterpartyType;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DeleteCounterpartyQuery\FindCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindByCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\EditCounterpartyCommand\EditCounterpartyCommandHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\SaveCounterpartyCommandHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\EditCounterpartyQuery\FindOneByIdCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand\DeleteCounterpartyCommandHandler;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\DTOCounterpartyObjCommand\CounterpartyObjCommand;

class CounterpartyController extends AbstractController
{
    /*Сохранения постовщика*/
    #[Route('saveCounterparty', name: 'save_counterparty')]
    public function saveCounterparty(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        SaveCounterpartyCommandHandler $saveCounterpartyCommandHandler
    ): Response {

        /*Форма сохранения постовщка*/
        $form_save_counterparty = $this->createForm(SaveCounterpartyType::class);

        /*Валидация формы */
        $form_save_counterparty->handleRequest($request);

        $id = null;
        if ($form_save_counterparty->isSubmitted()) {
            if ($form_save_counterparty->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $counterparty = $this->mapeCounterparty(
                        null,
                        $form_save_counterparty->getData()['name_counterparty'],
                        $form_save_counterparty->getData()['mail_counterparty'],
                        $form_save_counterparty->getData()['manager_phone'],
                        $form_save_counterparty->getData()['delivery_phone'],
                        $participant
                    );
                    $id = $saveCounterpartyCommandHandler
                        ->handler(new CounterpartyCommand($counterparty));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
            'id_handler' => $id
        ]);
    }

    /*Поиск постовщика*/
    #[Route('searchCounterparty', name: 'search_counterparty')]
    public function searchCounterparty(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindAllCounterpartyQueryHandler $findAllCounterpartyQueryHandler,
        FindByCounterpartyQueryHandler $findByCounterpartyQueryHandler,
        SearchCounterpartyQueryHandler $searchCounterpartyQueryHandler
    ): Response {

        /*Форма поиска постовщка*/
        $form_search_counterparty = $this->createForm(SearchCounterpartyType::class);

        /*Валидация формы */
        $form_search_counterparty->handleRequest($request);

        /*Выводим полный список поставщиков*/
        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
            $counterparty = $this->mapeCounterparty(
                null,
                null,
                null,
                null,
                null,
                $participant
            );
            $search_data = $findByCounterpartyQueryHandler
                ->handler(new CounterpartyQuery($counterparty));
            //$search_data = $findAllCounterpartyQueryHandler->handler();
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        if ($form_search_counterparty->isSubmitted()) {
            if ($form_search_counterparty->isValid()) {

                try {
                    $counterparty = $this->mapeCounterparty(
                        null,
                        $form_search_counterparty->getData()['name_counterparty'],
                        null,
                        null,
                        null,
                        $participant
                    );
                    $search_data = $searchCounterpartyQueryHandler
                        ->handler(new CounterpartyQuery($counterparty));
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
    #[Route('editCounterparty', name: 'edit_counterparty')]
    public function editCounterparty(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByIdCounterpartyQueryHandler $findOneByIdCounterpartyQueryHandler,
        EditCounterpartyCommandHandler $editCounterpartyCommandHandler
    ): Response {

        /*Форма Редактирования постовщка*/
        $form_edit_counterparty = $this->createForm(EditCounterpartyType::class);

        /*Валидация формы */
        $form_edit_counterparty->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);

            return $this->redirectToRoute('search_counterparty');
        }

        if (empty($form_edit_counterparty->getData())) {

            try {
                $counterparty = $this->mapeCounterparty(
                    $request->query->all()['id'],
                    null,
                    null,
                    null,
                    null,
                    $participant
                );
                $data_form_edit_counterparty = $findOneByIdCounterpartyQueryHandler
                    ->handler(new CounterpartyQuery($counterparty));
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
                    $counterparty = $this->mapeCounterparty(
                        $form_edit_counterparty->getData()['id'],
                        $form_edit_counterparty->getData()['name_counterparty'],
                        $form_edit_counterparty->getData()['mail_counterparty'],
                        $form_edit_counterparty->getData()['manager_phone'],
                        $form_edit_counterparty->getData()['delivery_phone'],
                        $participant
                    );
                    $id_handler = $editCounterpartyCommandHandler
                        ->handler(new CounterpartyCommand($counterparty));
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
    #[Route('deleteCounterparty', name: 'delete_counterparty')]
    public function deleteCounterparty(
        Request $request,
        FindCounterpartyQueryHandler $findCounterpartyQueryHandler,
        DeleteCounterpartyCommandHandler $deleteCounterpartyCommandHandler
    ): Response {

        try {

            $counterparty['counterparty'] = $findCounterpartyQueryHandler
                ->handler(new CounterpartyQuery($request->query->all()));

            $deleteCounterpartyCommandHandler
                ->handler(new CounterpartyObjCommand($counterparty));
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

    private function mapeCounterparty(
        $id,
        $name_counterparty,
        $mail_counterparty,
        $manager_phone,
        $delivery_phone,
        $participant
    ): array {
        $arr_counterparty['id'] = $id;
        $arr_counterparty['name_counterparty'] = $name_counterparty;
        $arr_counterparty['mail_counterparty'] = $mail_counterparty;
        $arr_counterparty['manager_phone'] = $manager_phone;
        $arr_counterparty['delivery_phone'] = $delivery_phone;
        $arr_counterparty['id_participant'] = $participant;

        return $arr_counterparty;
    }
}
