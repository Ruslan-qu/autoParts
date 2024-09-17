<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerPartNumbers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers\SavePartNumbersType;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\CreatePartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\CreateSavePartNumbersCommandHandler;

class PartNumbersController extends AbstractController
{
    /*Сохранения автодеталей*/
    #[Route('/savePartNumbers', name: 'save_part_numbers')]
    public function savePartNumbers(
        Request $request,
        CreateSavePartNumbersCommandHandler $createSaveCounterpartyCommandHandler
    ): Response {

        /* Форма сохранения */
        $form_save_part_numbers = $this->createForm(SavePartNumbersType::class);

        /*Валидация формы */
        $form_save_part_numbers->handleRequest($request);

        $arr_saving_information = [];
        if ($form_save_part_numbers->isSubmitted()) {
            if ($form_save_part_numbers->isValid()) {
                // dd($form_save_part_numbers->getData());
                $arr_saving_information = $createSaveCounterpartyCommandHandler
                    ->handler(new CreatePartNumbersCommand($form_save_part_numbers->getData()));
            }
        }

        return $this->render('partNumbers/savePartNumbers.html.twig', [
            'title_logo' => 'Добавление новой автодетали',
            'form_save_part_numbers' => $form_save_part_numbers->createView(),
            'arr_saving_information' => $arr_saving_information
        ]);
    }
}
