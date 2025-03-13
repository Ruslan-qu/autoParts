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
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserRegistrationCommandHandler $userRegistrationCommandHandler
    ): Response {

        $user = new Participant();
        $form_registration_participant = $this->createForm(RegistrationFormType::class, $user);
        $form_registration_participant->handleRequest($request);

        if ($form_registration_participant->isSubmitted()) {
            if ($form_registration_participant->isValid()) {


                try {

                    $id_handler = $userRegistrationCommandHandler
                        ->handler(new ParticipantCommand($form_registration_participant->getData()));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form_registration_participant->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email

                return $this->redirectToRoute('main_page');
            }
        }

        return $this->render('@registerParticipant/register.html.twig', [
            'title_logo' => 'Регистрация',
            'registrationForm' => $form_registration_participant,
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
