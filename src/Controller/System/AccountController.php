<?php

namespace App\Controller\System;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $uuid = Uuid::uuid4()->toString();

            // Set ID directly on the User object
            $user->setId($uuid);

            // Get form data
            $formData = $form->getData();

            // Set other values from form data
            $user->setEmail($formData->getEmail());
            $user->setName($formData->getName());
            $user->setPassword($formData->getPassword());

            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('account/index.html.twig', [
            'user_form' => $form,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
