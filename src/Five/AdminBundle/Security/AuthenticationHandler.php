<?php

namespace Five\AdminBundle\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;
#use Symfony\Component\Security\Core\SecurityContext;

class AuthenticationHandler implements AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface
{
    protected $Session;
    protected $RequestStack;
    protected $SecurityContext;

    public function onAuthenticationFailure( Request $request, AuthenticationException $exception)
    {       
        $referer = $request->headers->get( 'referer' );
        $this->getSession()->setFlash( 'error', $exception->getMessage() );

        return new RedirectResponse( '/' );
    }

    public function onLogoutSuccess(Request $request) 
    {
        $resp = new RedirectResponse( '/' );

        //$resp->headers->setCookie( new Cookie( 'user_offer_model', '' ) );
        $this->getSecurityContext()->setToken( null );
        $this->getSession()->invalidate();

        return $resp;
    }

    public function setRequestStack( RequestStack $RequestStack )
    {
        $this->RequestStack = $RequestStack;
    }

    public function getRequest()
    {
        return $this->RequestStack->getCurrentRequest();
    }

    public function setSession( $Session )
    {
        $this->Session = $Session;
    }

    public function getSession()
    {
        return $this->Session;
    }

    public function setSecurityContext( $SecurityContext )
    {
        $this->SecurityContext = $SecurityContext;
    }

    public function getSecurityContext()
    {
        return $this->SecurityContext;
    }    
}