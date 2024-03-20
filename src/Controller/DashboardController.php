<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) return $this->redirectToRoute('app_login');

        return $this->render('dashboard/index.html.twig', [
            'title' => 'Dashboard',
            'icon' => 'speedometer2'
        ]);
    }
}
