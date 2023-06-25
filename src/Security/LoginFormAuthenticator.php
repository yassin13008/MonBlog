<?php

namespace App\Security;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security.login';
    private FlashBagInterface $flashbag;

    public function __construct(private UrlGeneratorInterface $urlGenerator, private RequestStack $request, private FormFactoryInterface $formFactory)
    {
        $this->flashbag = $this->request->getSession()->getFlashBag();
    }

    public function authenticate(Request $request): Passport
    {
       $form = $this->formFactory->createNamed('', LoginType::class);
       $form->handleRequest($request);
    
       $data = $form->getData();
    
       $email = $data['email'];
       $plainPassword = $data['plainPassword'];
    
       $request->getSession()->set(Security::LAST_USERNAME, $email);
    
       return new Passport(
           new UserBadge($email),
           new PasswordCredentials($plainPassword),
           [
               new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
           ]
       );
    }
    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
       $this->flashbag->add('success', 'Connexion réussie');
    
       if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
           return new RedirectResponse($targetPath);
       }
    
       $route = $token->getUser()->hasRole('ROLE_ADMIN') ? 'admin.index' : 'home.index';
    
       return new RedirectResponse($this->urlGenerator->generate($route));
    }
    
    

    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') 
     && $this->getLoginUrl($request) === $request->getPathInfo();
    }


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
