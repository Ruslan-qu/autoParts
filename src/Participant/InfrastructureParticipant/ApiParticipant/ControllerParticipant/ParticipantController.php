<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\ControllerParticipant;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant\EditParticipantType;
use App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant\SearchParticipantType;
use App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery\ParticipantQuery;
use App\Participant\ApplicationParticipant\QueryParticipant\EditParticipantQuery\FindParticipantQueryHandler;
use App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantObjQuery\ParticipantObjQuery;
use App\Participant\ApplicationParticipant\QueryParticipant\SearchParticipantQuery\FindAllParticipantQueryHandler;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantCommand\ParticipantCommand;
use App\Participant\ApplicationParticipant\CommandsParticipant\EditParticipantCommand\EditParticipantCommandHandler;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantObjCommand\ParticipantObjCommand;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DeleteParticipantCommand\DeleteParticipantCommandHandler;

class ParticipantController extends AbstractController
{

    /*Поиск пользователя*/
    #[Route('searchParticipant', name: 'search_participant')]
    public function searchParticipant(
        Request $request,
        FindAllParticipantQueryHandler $findAllParticipantQueryHandler,
    ): Response {

        /*Форма*/
        $form_search_participant = $this->createForm(SearchParticipantType::class);

        /*Валидация формы */
        $form_search_participant->handleRequest($request);

        /*Выводим полный список*/
        try {
            $search_data = $findAllParticipantQueryHandler->handler();
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        if ($form_search_participant->isSubmitted()) {
            if ($form_search_participant->isValid()) {

                $search_data = $form_search_participant->getData();
            }
        }
        // dd($search_data);
        return $this->render('@participant/searchParticipant.html.twig', [
            'title_logo' => 'Поиск пользователя',
            'form_search_participant' => $form_search_participant->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования пользователя*/
    #[Route('editParticipant', name: 'edit_participant')]
    public function editParticipant(
        Request $request,
        FindParticipantQueryHandler $findParticipantQueryHandler,
        EditParticipantCommandHandler $editParticipantCommandHandler
    ): Response {

        /*Форма Редактирования*/
        $form_edit_participant = $this->createForm(EditParticipantType::class);

        /*Валидация формы */
        $form_edit_participant->handleRequest($request);

        if (empty($form_edit_participant->getData())) {
            try {
                $data_form_edit_participant = $findParticipantQueryHandler
                    ->handler(new ParticipantQuery($request->query->all()));
            } catch (HttpException $e) {

                $this->errorMessageViaSession($e);
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_participant = $request->request->all()['edit_participant'];
        }

        $id_handler = null;
        if ($form_edit_participant->isSubmitted()) {
            if ($form_edit_participant->isValid()) {

                try {

                    $id_handler = $editParticipantCommandHandler
                        ->handler(new ParticipantCommand($form_edit_participant->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@participant/editParticipant.html.twig', [
            'title_logo' => 'Изменение данных пользователя',
            'form_edit_participant' => $form_edit_participant->createView(),
            'id_handler' => $id_handler,
            'data_form_edit_participant' => $data_form_edit_participant,
        ]);
    }

    /*Удаление пользователя*/
    #[Route('deleteParticipant', name: 'delete_participant')]
    public function deleteParticipant(
        Request $request,
        FindParticipantQueryHandler $findParticipantQueryHandler,
        DeleteParticipantCommandHandler $deleteParticipantCommandHandler
    ): Response {

        try {

            $participant['participant'] = $findParticipantQueryHandler
                ->handler(new ParticipantQuery($request->query->all()));

            $deleteParticipantCommandHandler
                ->handler(new ParticipantObjCommand($participant));
            $this->addFlash('delete', 'Пользователь удален');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('searchparticipant');
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
