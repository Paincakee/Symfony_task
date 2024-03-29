<?php

namespace App\Controller\timeManagement;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Attribute\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {

        return $this->render('calendar/index.html.twig', [
            'title' => 'Calendar',
            'icon' => 'bi-calendar',
        ]);
    }
}
