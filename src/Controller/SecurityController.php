<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastEmail,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ["GET"])]
    public function logout(Request $request): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/security/change-password', name: 'app_change_password', methods: ['GET', 'POST'])]
    # remember: this route not accessable with cookie or unauthenticated user
    #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('newPassword')->getData() === $form->get('currentPassword')->getData()) {
                $this->addFlash('Danger', "Password doesn't change!");
                return $this->redirectToRoute('app_home');
            }

            # Encode the new password.
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                )
            );

            $entityManager->flush();
            $this->addFlash('Success', "Password updated!");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
