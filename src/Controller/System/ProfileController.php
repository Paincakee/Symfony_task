<?php

namespace App\Controller\System;

use App\Form\Account\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    private entityManagerInterface $entityManager;
    public function __construct(entityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('account/profile.html.twig', [
            'title' => 'Profile',
            'icon' => 'person-circle',
            'profileForm' => $form->createView(),
        ]);
    }

}
