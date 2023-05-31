<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/update', name: 'profile_update')]
    public function update(): Response
    {
        //TODO update method
        $user = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/delete', name: 'profile_delete')]
    public function delete(UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $userRepository->remove($user, true);

        return $this->redirectToRoute('main_home');
    }
}
