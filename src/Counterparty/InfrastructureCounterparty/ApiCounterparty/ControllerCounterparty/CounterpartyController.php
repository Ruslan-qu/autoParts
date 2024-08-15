<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\ControllerCounterparty;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SaveCounterpartyType;
use App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty\CreateSaveCounterpartyCommand;
use App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty\CreateSaveCounterpartyCommandHandler;

class CounterpartyController extends AbstractController
{
    #[Route('/saveCounterparty', name: 'save_counterparty')]
    public function saveCounterparty(
        Request $request,
        CreateSaveCounterpartyCommandHandler $createSaveCounterpartyCommandHandler
    ): Response {

        /*Форма сохранения постовщка*/
        $form_save_counterparty = $this->createForm(SaveCounterpartyType::class);

        /*Валидация формы */
        $form_save_counterparty->handleRequest($request);

        if ($form_save_counterparty->isSubmitted()) {
            if ($form_save_counterparty->isValid()) {
                //dd($request->request->all());
                dd($createSaveCounterpartyCommandHandler
                    ->handler(new CreateSaveCounterpartyCommand($request->request->all()['save_counterparty'])));
            }
        }

        return $this->render('counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
        ]);
    }
}
