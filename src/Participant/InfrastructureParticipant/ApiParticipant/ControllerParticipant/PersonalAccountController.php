<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\ControllerParticipant;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery\ParticipantQuery;
use App\Participant\ApplicationParticipant\QueryParticipant\UserExtractionQuery\UserExtractionQueryHandler;
use App\Participant\ApplicationParticipant\QueryParticipant\EditParticipantQuery\FindParticipantQueryHandler;
use App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant\EditParticipantPersonalAccountType;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantCommand\ParticipantCommand;
use App\Participant\ApplicationParticipant\CommandsParticipant\DeleteParticipantCommand\DeleteParticipantCommandHandler;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantObjCommand\ParticipantObjCommand;
use App\Participant\ApplicationParticipant\CommandsParticipant\EditParticipantPersonalAccountCommand\EditParticipantPersonalAccountCommandHandler;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class PersonalAccountController extends AbstractController
{
    #[Route('personalAccount', name: 'personal_account')]
    public function personalAccount(
        UserExtractionQueryHandler $userExtractionQueryHandler
    ): Response {

        /*Выводим полный список*/
        try {
            $user_data = $userExtractionQueryHandler->handler();
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->render('@participant/personalAccount.html.twig', [
            'title_logo' => 'Личный кабинет',
            'user_data' => $user_data,

        ]);
    }

    /*Редактирования пользователя в Личном кабинете*/
    #[Route('editParticipantPersonalAccount', name: 'edit_participant_personal_account')]
    public function editParticipantPersonalAccount(
        Request $request,
        UserExtractionQueryHandler $userExtractionQueryHandler,
        EditParticipantPersonalAccountCommandHandler $editParticipantPersonalAccountCommandHandler
    ): Response {

        /*Форма Редактирования*/
        $form_edit_participant_personal_account = $this->createForm(EditParticipantPersonalAccountType::class);

        /*Валидация формы */
        $form_edit_participant_personal_account->handleRequest($request);

        if (empty($form_edit_participant_personal_account->getData())) {
            try {
                $data_form_edit_participant_personal_account = $userExtractionQueryHandler->handler();
            } catch (HttpException $e) {

                $this->errorMessageViaSession($e);
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_participant_personal_account = $request->request->all()['edit_participant_personal_account'];
        }
        //dd($request);
        $id = null;
        if ($form_edit_participant_personal_account->isSubmitted()) {
            if ($form_edit_participant_personal_account->isValid()) {

                try {

                    $id = $editParticipantPersonalAccountCommandHandler
                        ->handler(new ParticipantCommand($form_edit_participant_personal_account->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@participant/editParticipantPersonalAccount.html.twig', [
            'title_logo' => 'Изменение данных пользователя',
            'form_edit_participant_personal_account' => $form_edit_participant_personal_account->createView(),
            'id' => $id,
            'data_form_edit_participant_personal_account' => $data_form_edit_participant_personal_account,
        ]);
    }

    /*Удаление пользователя*/
    #[Route('deleteParticipantPersonalAccount', name: 'delete_participant_personal_account')]
    public function deleteParticipantPersonalAccount(
        Request $request,
        FindParticipantQueryHandler $findParticipantQueryHandler,
        DeleteParticipantCommandHandler $deleteParticipantCommandHandler
    ): Response {

        try {

            $participant['participant'] = $findParticipantQueryHandler
                ->handler(new ParticipantQuery($request->query->all()));

            $deleteParticipantCommandHandler
                ->handler(new ParticipantObjCommand($participant));
            $this->addFlash('delete', 'Профиль удален');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('app_register');
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
