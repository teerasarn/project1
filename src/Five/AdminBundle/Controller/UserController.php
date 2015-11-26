<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Five\AdminBundle\Entity\User;
use Five\AdminBundle\Form\UserType;

class UserController extends Controller
{

    public function checkAccessGranted( $redirectLogin = false )
    {
        if( false === $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' ) )
        {
            throw new AccessDeniedException();

            if( $redirectLogin )
            {
                return new RedirectResponse( $this->generateUrl( 'five_admin_home' ) );
                exit();
            }
            else
            {
                throw new AccessDeniedException();
            }
        }        
    }

    public function getSecurityContext()
    {
        return $this->container->get( 'security.context' );
    }

    public function userCreateAction( Request $request )
    {
        $this->checkAccessGranted( true );

        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $deviceType = $this->container->get( 'session' )->get( 'isMobile', 0 );
        $deviceType = ( $deviceType == 1 ? 'mobile' : 'desktop' );

        if ($form->isValid() ) {
            $factory = $this->get( 'security.encoder_factory' );
            $encoder = $factory->getEncoder( $entity );
            $password = $encoder->encodePassword( $entity->getPlainPassword(), $entity->getSalt() );
            $entity->setPassword( $password );
            $entity
                ->setEnabled( true )
                ->setIpAddress( $this->getRequest()->getClientIp() )
                ->setUserAgent( $this->getRequest()->headers->get( 'User-Agent' ) )
                ->setDeviceType( $deviceType )
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

    
            $response = array(
                'status'           => 'success',
                'user'             => $entity->toArray(),
                'password'         => $entity->getPlainPassword()
            );

        //return $this->JsonResponse( $response );


        //return $resp;            
            
        }

        return $this->render( 'FiveAdminBundle:User:new.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ) );
/*        $errs = (string) $form->getErrors(true, false);
        $errorsExpl = explode( "\n", $errs );
        $errors = array();
        $errors = array();

        foreach( $errorsExpl as $error )
        {
            if( strstr($error, "ERROR:") !== false )
            {
                $expl = explode( "ERROR:", $error );
                $errors[] =  $this->get( 'translator' )->trans( trim( end( $expl ) ), array(), 'validators' );
            }
        }        

        $response = array(
            'status'           => 'error',
            'errors'           => $errors
        );

        return $this->JsonResponse( $response );*/
    }

    private function createCreateForm(User $entity)
    {        
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('_user_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;        
    }

    public function listAction( $options )
    {
        $this->checkAccessGranted( true );
        //var_dump($this->container->get('security.context')->isGranted( 'ROLE_ADMIN' ) );
/*        $chains = $this->container->get( 'five_admin.transport_chain' );
        $user_list = $chains->getTransport( 'user_list' );
        $params = $this->container->getParameter( $user_list[ 'attributes' ][ 'attributes' ] );*/
        //$chains = $this->container->get( 'five_admin.transport_chain' )->getTransport( 'user_list' );
        
        //var_dump( $params );
        $params = $this->container->getParameter( $options );

        return $this->get( 'five.form_manager' )->listEntities( 
            //$params[ 'entity' ],
            $params
        );
    }

    public function listTransAction( $options )
    {
/*        $chains = $this->container->get( 'five_admin.transport_chain' );
        $user_list = $chains->getTransport( 'user_list' );
        $params = $this->container->getParameter( $user_list[ 'attributes' ][ 'attributes' ] );*/
        //$chains = $this->container->get( 'five_admin.transport_chain' )->getTransport( 'user_list' );
        
        //var_dump( $params );

        $params = $this->container->getParameter( $options );

        return $this->get( 'five.form_manager' )->listEntities( 
            //$params[ 'entity' ],
            $params
        );
    }    

    public function createAction( Request $Request, $options )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->create( 
            $this->get( $params[ 'form' ] ),
            //$params[ 'entity' ],
            $params
        );        
    }

    public function editAction( Request $Request, $id, $options )
    {
        $this->checkAccessGranted( true );

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository( 'FiveAdminBundle:User' )->find( $id );

            $form = $this->createForm(new UserType(), $entity, array(
                'action' => $this->generateUrl('five_admin_user_edit', array( 'id' => $id )),
                'method' => 'POST',
            ));
            
            $form->add('submit', 'submit', array('label' => 'Update'));
            $deviceType = $this->container->get( 'session' )->get( 'isMobile', 0 );
            $deviceType = ( $deviceType == 1 ? 'mobile' : 'desktop' );
            $params = $this->container->getParameter( $options );

            $form->handleRequest($Request);

            if ($form->isValid() ) 
            {
                if( $entity->getPlainPassword() != null && $entity->getPlainPassword() != '' )
                {
                    $factory = $this->get( 'security.encoder_factory' );
                    $encoder = $factory->getEncoder( $entity );
                    $password = $encoder->encodePassword( $entity->getPlainPassword(), $entity->getSalt() );
                    $entity->setPassword( $password );                    
                }

                $entity
                    ->setEnabled( true )
                    ->setIpAddress( $this->getRequest()->getClientIp() )
                    ->setUserAgent( $this->getRequest()->headers->get( 'User-Agent' ) )
                    ->setDeviceType( $deviceType )
                ;

                
                $em->persist($entity);
                $em->flush();

        
                $response = array(
                    'status'           => 'success',
                    'user'             => $entity->toArray(),
                    'password'         => $entity->getPlainPassword(),
                );

            //return $this->JsonResponse( $response );


                return new RedirectResponse( $this->generateUrl( 'five_admin_user_list' ) );
                
            }

            return $this->render( 'FiveAdminBundle:Action:edit.html.twig', array(
                'form'        => $form->createView(),
                'form_action' =>  array( 'path' => 'five_admin_user_edit', 'parameters' =>  array( 'id' => $id ) ),
                'entity' => $entity,
                'options' => $params

            ) );            
        
    }

    public function deleteAction( Request $Request, $id, $options )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->deleteAction(
            $this->get( $params[ 'form' ] ),
            $params,
            $id
        );        
    }      
}
