<?php

namespace App\Main\InfrastructureMain\ApiMain\ControllerMain;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_page')]
    public function index(): Response
    {
        return $this->render('@main/main.html.twig', [
            'title_logo' => 'Главная'
        ]);
    }
}
