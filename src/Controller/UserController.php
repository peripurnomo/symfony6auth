<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/profile', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('/{paramUsername}', name: 'profile', methods: ['GET'])]
    #[Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")]
    #[ParamConverter('user', options: ['mapping' => ['paramUsername' => 'username']])]
    public function profile(Request $request, User $user): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
