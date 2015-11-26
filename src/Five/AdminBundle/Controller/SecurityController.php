<?php

namespace Five\AdminBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SecurityController extends ContainerAware
{
    protected $options = array();

    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
        ));
    }

    public function checkLoginStateAction()
    {
        $isLogged = $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' );
        $User     = $this->container->get( 'security.context' )->getToken()->getUser();

            $response = array(
                'status' => 'false',
                'errors' => array()
            );

        if( $isLogged )
        {
            
            $response = array(
                'status'      => 'true',
                'errors'      => array(),
                'user'        => $User->toArray(),
            ); 

            $response = $this->prepareResponse( $response );           
        }

        $resp = new JsonResponse( $response );
$resp->setCache(array(
    'etag'          => null,
    'last_modified' => new \DateTime(),
    'max_age'       => 10,
    's_maxage'      => 10,
    //'public'        => true,
     'private'    => true,
));


        return $resp;
    }

    public function loginSuccessAction( Request $request )
    {
            $User = $this->container->get( 'security.context' )->getToken()->getUser();

            $response = array(
                'status' => 'success',
                'errors' => array()
            );

            $response = $this->prepareResponse( $response );

        $resp = new JsonResponse( $response );
$resp->setCache(array(
    'etag'          => null,
    'last_modified' => new \DateTime(),
    'max_age'       => 10,
    's_maxage'      => 10,
    //'public'        => true,
     'private'    => true,
));


        return $resp;
    }

    public function loginErrorAction( Request $Request )
    {

            $response = array(
                'status' => 'error',
                'errors' => array()
            );

        $resp = new JsonResponse( $response );
$resp->setCache(array(
    'etag'          => null,
    'last_modified' => new \DateTime(),
    'max_age'       => 10,
    's_maxage'      => 10,
    //'public'        => true,
     'private'    => true,
));


        return $resp;
    }    

    public function loginResponseAction( Request $Request )
    {
        $User = $this->container->get( 'security.context' )->getToken()->getUser();

            $response = array(
                'status' => 'error',
                'errors' => array()
            );

        if( $User instanceof UserInterface )
        {            

            $response = array(
                'status' => 'success',
                'errors' => array()
            );

            $response = $this->prepareResponse( $response );
        }

        $resp = new JsonResponse( $response );
$resp->setCache(array(
    'etag'          => null,
    'last_modified' => new \DateTime(),
    'max_age'       => 10,
    's_maxage'      => 10,
    //'public'        => true,
     'private'    => true,
));


        return $resp;
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = 'FiveAdminBundle:Security:login.html.twig';

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction( Request $request )
    {
/*        $request->cookies->set( 'user_offer_model', null );
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();        */
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    public function prepareResponse( array $options = array() )
    {
        $resolver = new OptionsResolver();
        
        $this->setDefaultOptions( $resolver );
        $resolver->replaceDefaults( $options );

        $this->options = $resolver->resolve( $options );

        return $this->options;
//        return $response;
    }    

    protected function setDefaultOptions( OptionsResolverInterface $resolver )
    {   
        //$this->get( 'banquier.shift' );
        $User  = $this->container->get( 'security.context' )->getToken()->getUser();
        $granted = $this->container->get( 'security.context' )->isGranted( 'ROLE_USER' );

        $resolver->setDefaults( array(
            'User'                => $User,
            'userLoggedIn'        => $granted,
        ) );
    }    
}