<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private FormFactoryInterface $formFactory
       ){}

    #[Route(path: '/login', name: 'security.login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $lastEmail = $utils->getLastUsername();
        $form = $this->formFactory->createNamed('', LoginType::class, ['email' => $lastEmail]);
       $error = $utils->getLastAuthenticationError();
    
       return $this->render('security/login.html.twig', [
           'loginForm' => $form->createView(),
           'error' => $error
       ]);
    }
    

    #[Route(path: '/logout', name: 'security.logout')]
    public function logout(): void
    {
    
    }
}
