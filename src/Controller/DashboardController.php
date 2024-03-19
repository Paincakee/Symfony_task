<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    #[isGranted(roles: ['ROLE_USER'])]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'title' => 'Dashboard',
            'icon' => 'speedometer2'
        ]);
    }
}
