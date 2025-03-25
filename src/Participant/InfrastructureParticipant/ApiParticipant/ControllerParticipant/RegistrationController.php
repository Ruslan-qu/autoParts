<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\ControllerParticipant;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant\RegistrationFormType;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOParticipantCommand\ParticipantCommand;
use App\Participant\ApplicationParticipant\CommandsParticipant\UserRegistrationCommand\UserRegistrationCommandHandler;

class RegistrationController extends AbstractController
{
    /*Регистрация пользователя*/
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserRegistrationCommandHandler $userRegistrationCommandHandler,
        Participant $participant
    ): Response {

        $form_registration_participant = $this->createForm(RegistrationFormType::class, $participant);
        $form_registration_participant->handleRequest($request);

        $id = 0;
        if ($form_registration_participant->isSubmitted()) {
            if ($form_registration_participant->isValid()) {

                try {

                    $id = $userRegistrationCommandHandler
                        ->handler(new ParticipantCommand($form_registration_participant->all()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }

                //return $this->redirectToRoute('main_page');
            }
        }

        return $this->render('@registerAuthenticationParticipant/register.html.twig', [
            'title_logo' => 'Регистрация',
            'registrationForm' => $form_registration_participant,
            'id' => $id
        ]);
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
