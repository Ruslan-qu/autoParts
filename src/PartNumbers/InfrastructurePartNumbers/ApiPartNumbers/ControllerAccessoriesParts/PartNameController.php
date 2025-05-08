<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartName\SavePartNameType;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\UserExtractionQuery\UserExtractionQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand\PartNameCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\SavePartNameCommand\SavePartNameCommandHandler;

class PartNameController extends AbstractController
{
    /*Сохранения автодеталей*/
    #[Route('/savePartName', name: 'save_part_name')]
    public function savePartName(
        Request $request,
        SavePartNameCommandHandler $savePartNameCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_part_name = $this->createForm(SavePartNameType::class);

        /*Валидация формы */
        $form_save_part_name->handleRequest($request);

        $id = null;
        if ($form_save_part_name->isSubmitted()) {
            if ($form_save_part_name->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    dd($participant);
                    $id = $savePartNameCommandHandler
                        ->handler(new PartNameCommand($form_save_part_name->getData()));
                } catch (HttpException $e) {
                    // dd($e);
                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@partName/savePartName.html.twig', [
            'title_logo' => 'Добавление названия детали',
            'form_save_part_name' => $form_save_part_name->createView(),
            'id' => $id
        ]);
    }
}
