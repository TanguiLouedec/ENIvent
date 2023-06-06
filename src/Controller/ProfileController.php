<?php

namespace App\Controller;

use App\Entity\State;
use App\Form\EventType;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Utils\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/myprofile', name: 'app_myprofile')]
    public function index(): Response
    {
        $user = $this->getUser();
        $actualUser = $this->getUser()->getId();

        return $this->render('profile/profile.html.twig', [
            'user' => $user, 'actualUser'=>$actualUser
        ]);
    }

    #[Route('/profile/{id}', name: 'app_profile')]
    public function seeProfile(int $id, UserRepository $userRepository): Response
    {
        $actualUser = $this->getUser()->getId();
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException("Oops! User not found");
        }
        return $this->render('profile/profile.html.twig', ['user' => $user, 'actualUser'=>$actualUser]);
    }

    #[Route('/profile/update', name: 'profile_update')]
    public function update(
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        Request $request,
        Uploader $uploader
    ): Response
    {
        $user = $this->getUser();
        $userForm = $this->createForm(RegistrationFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            /**
             * @var UploadedFile $file
             */
            $file = $userForm->get('profilePicture')->getData();
            if($file){
                $newFileName = $uploader->save($file, $user->getFirstName() . '-' . $user->getLastName(), $this->getParameter('upload_profile_picture'));
                $user->setProfilePicture($newFileName);
            } else {
                $user->setProfilePicture('DefProfPic.bmp');
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userForm->get('password')->getData()
                )
            );

            $userRepository->save($user, true);
            $this->addFlash('success', 'Profil successfully updated !');
            return $this->redirectToRoute('app_myprofile', ['id' => $user->getId()]);
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