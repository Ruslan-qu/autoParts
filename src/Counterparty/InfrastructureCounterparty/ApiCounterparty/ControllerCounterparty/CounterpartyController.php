<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\ControllerCounterparty;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty\SaveCounterpartyType;

class CounterpartyController extends AbstractController
{
    #[Route('/saveCounterparty', name: 'save_counterparty')]
    public function saveCounterparty(): Response
    {

        /*Подключаем формы */
        $form_save_counterparty = $this->createForm(SaveCounterpartyType::class);



        return $this->render('counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
        ]);
    }
}
