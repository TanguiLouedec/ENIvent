<?php

namespace App\Controller;

use App\Entity\State;
use App\Form\EventType;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function update(UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();
        $userForm = $this->createForm(RegistrationFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $userRepository->save($user, true);
            $this->addFlash('success', 'Profil successfully updated !');
            return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
        }
        return $this->render('profile/update.html.twig', ['userFormUpdate' => $userForm->createView(), 'user' => $user]);
    }

    #[Route('/profile/delete', name: 'profile_delete')]
    public function delete(UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $userRepository->remove($user, true);

        return $this->redirectToRoute('main_home');
    }
}