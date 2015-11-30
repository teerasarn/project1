<?php
namespace Five\AdminBundle\Manager;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FormManager extends Controller
{

    protected $RequestStack;
    protected $Doctrine;
    protected $Templating;
    protected $Router;
    protected $uploadsPath = null;
    protected $mediasPath  = null;
    protected $uploadableManager;

    protected $deleteMethodsForm     = array( 'DELETE', 'POST' );
    protected $createEditMethodsForm = array( 'PATCH', 'PUT', 'POST' );
    protected $SecurityContext;

    public function __construct( RequestStack $RequestStack, $Doctrine, $Templating, $Router )
    {
        $this->RequestStack = $RequestStack;
        $this->Doctrine     = $Doctrine;
        $this->Templating   = $Templating;
        $this->Router       = $Router;
    }

    public function checkAccessGranted( $redirectLogin = false )
    {
        if( false === $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' ) )
        {

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

    public function setUploadsPath( $path )
    {
        $this->uploadsPath = $path;

        return $this;
    }

    public function getUploadsPath()
    {
        return $this->uploadsPath;
    }

    public function setMediasPath( $path )
    {
        $this->mediasPath = $path;

        return $this;
    }

    public function getMediasPath()
    {
        return $this->mediasPath;
    }        

    public function setUploadableManager( $manager )
    {
        $this->uploadableManager = $manager;

        return $this;
    }

    public function getUploadableManager()
    {
        return $this->uploadableManager;
    }    

    public function getDoctrine()
    {
        return $this->Doctrine;
    }

    public function getOptionsEntity( $options, $id = null, $forceAction = null )
    {
/*        if( isset( $options[ 'entity' ] ) )
        {
            return ( is_string( $options[ 'entity' ] ) ? $this->Repo( $options[ 'entity' ] )->findAll() : $options[ 'entity' ] );
        }*/

        $entity   = null;
        $entities = null;
        $action   = null;

        if( isset( $options[ 'entities' ] ) || $forceAction == 'list' )
        {
            $action   = 'list';
            $entities = ( is_string( $options[ 'entities' ] ) ? $this->Repo( $options[ 'entities' ] )->findAll() : $options[ 'entities' ] );
        }

        if( isset( $options[ 'entity' ] ) )
        {
            if( ( is_numeric( $id ) && $forceAction != 'create' ) || in_array( $forceAction, array( 'edit', 'delete' ) ) )
            {
                $action = 'edit';
                $entity = $this->Repo( $options[ 'entity' ] )->find( $id );
                $options[ 'form_action' ][ 'parameters' ][ 'id' ] = $id;
            }
            else
            {
                $action = 'create';
                $entity = ( is_string( $options[ 'entity' ] ) ? new $options[ 'entity' ] : $options[ 'entity' ] );
            }
        }

        $options[ 'entity' ]   = $entity;
        $options[ 'entities' ] = $entities;
        $options[ 'action' ]   = $action;
        $options[ 'id' ]       = $id;
        
        return $options;
    }

    public function listEntities( $options )
    {
        //if( $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' )  )
        //{        
            $this->checkAccessGranted( true );

            $options = $this->getOptionsEntity( $options );

            return $this->render( $options[ 'template' ], array(
                'entities' => $options[ 'entities' ],
                'options'  => $options
            ) );   
/*        }
        else
        {
            return new RedirectResponse( 'five_admin_home' );
        } */       
    }

    public function edit( $form, $options, $id )
    {
        return $this->handleCreateOrEditAction( $form, $options, $id );   
    }

    public function handleCreateOrEditAction( $form, $options, $id = null, $forceAction = null )
    {
        // if( $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' )  )
        // {        
            $this->checkAccessGranted( true );

            $options = $this->getOptionsEntity( $options, $id );
            $action  = $options[ 'action' ];
            $entity  = $options[ 'entity' ];        
            $form    = $this->handleFormPostLike( $form, $action, $entity );


            if( $form->isValid() )
            {

                if( $form->has( 'mapPicture' ) )
                {            
                    if( !$this->isPictureFieldEmpty( $form, 'mapPicture' ) )
                    {
                        $this->save( $entity->getMapPicture() );
                    }
                    else
                    {
                        $entity->setMapPicture(null);
                    }
                }

                if( $form->has( 'discoverPicture' ) )
                {            
                    if( !$this->isPictureFieldEmpty( $form, 'discoverPicture' ) )
                    {
                        $this->save($entity->getDiscoverPicture());
                    }
                    else{
                        $entity->setDiscoverPicture(null);
                    }            
                }

                if( $form->has( 'picture' ) )
                {            
                    if( !$this->isPictureFieldEmpty( $form, 'picture' ) )
                    {
                        $this->save($entity->getPicture());
                    }
                    else{
                        $entity->setPicture(null);
                    }            
                }            
    /*            if( $form->has( 'mapPicture' ) || $form->has( 'discoveryPicture' ) )
                {
                    if( $form->get( 'mapPicture' )->getData() != null )                   
                    {
                        if( $form->get( 'mapPicture' )->getData()->getFile() != null  )
                        {
                            $this->save( $entity->getMapPicture() );
                        }
                    }
                    var_dump($form->get( 'mapPicture' )->getData()->getFile());
                    $this->save($entity->getMapPicture());
                    $this->save($entity->getDiscoverPicture());              
                }*/

                $this->save( $entity );
                return $this->handleSuccessRedirect( $options );
            }

            return $this->render( $options[ 'template' ], array(
                'entity'      => $entity,
                'form'        => $form->createView(),
                'form_action' => $options[ 'form_action' ],
                'options'     => $options
            ) );
        // }
        // else
        // {
        //     return new RedirectResponse( 'five_admin_home' );
        // }        
    }

    public function isPictureFieldEmpty( $form, $field )
    {
        if( $form->has( $field ) )
        {
            if( $form->get( $field )->getData() != null )                   
            {
                if( $form->get( $field )->getData()->getFile() != null  )
                {
                    return false;
                }
            }
        }

        return true;
    }

/*    public function isPictureFieldEmptyIfSet( $form, $field )
    {
        if( $form->has( $field ) )
        {
            if( $form->has( 'mapPicture' )  )
        }
    }*/

    public function handleFormPostLike( $form, $crud = null, $entity = null )
    {
        $cruds = array( 'edit' => 'Update', 'create' => 'Create', 'delete' => 'Delete' );

        if( in_array( strtolower( $crud ), array_keys( $cruds ) ) )
        {
            $form->setData( $entity );            
            $form->add( 'submit', 'submit', array( 
                'label' => $cruds[ strtolower( $crud ) ] 
            ) );
        }

        if( $this->isRequestPostLikeMethod( $this->getRequest() ) )
        {
            $form->handleRequest( $this->getRequest() );            
        }

        return $form;
    }

    public function create( $form, $options )
    {
        return $this->handleCreateOrEditAction( $form, $options );   
    }

    public function handleSuccessRedirect( $options )
    {
        $path   = $options[ 'success_action' ][ 'path' ];
        $params = $options[ 'success_action' ][ 'parameters' ];
        $params = array_merge( $params, array( 'alert_type' => 'success' ) );

        return $this->redirect( 
            $this->generateUrl( 
                $path, 
                $params
            ) 
        );
    }

    public function deleteAction( $form, $options, $id )
    {
        // if( $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' )  )
        // { 
            $this->checkAccessGranted( true );       

            $options[ 'form_action' ][ 'parameters' ][ 'id' ] = $id;
            $form = $this->createDeleteForm( $form, $options, $id );

            if( in_array( $this->getRequest()->getMethod(), $this->deleteMethodsForm ) )
            {
                $form->handleRequest( $this->getRequest() );
        
                if( $form->isValid() )
                {
                    $em     = $this->DoctrineM();
                    $entity = $this->Repo( $options[ 'entity' ] )->find( $id );

                    if( $entity )
                    {
                        $em->remove( $entity );
                        $em->flush();
                    }
        /*            if (!$entity) {
                        throw $this->createNotFoundException('Unable to find User entity.');
                    }*/

                    return $this->handleSuccessRedirect( $options );
                }
            }        

            return $this->render( $options[ 'template' ], array(
                'form'        => $form->createView(),
                'options'     => $options,
                'id'          => $id
            ) );
        // }
        // else
        // {
        //     return new RedirectResponse( 'five_admin_home' );
        // }
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm( $form, $options, $id )
    {
        return $form
            ->setAction( $this->generateUrl( $options[ 'form_action' ][ 'path' ], array( 'id' => $id ) ) )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->Templating->renderResponse($view, $parameters, $response);
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->Router->generate($route, $parameters, $referenceType);
    }

    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    } 

    public function setRequestStack( RequestStack $RequestStack )
    {
        $this->RequestStack = $RequestStack;
    }

    public function getRequest()
    {
        return $this->RequestStack->getCurrentRequest();
    }

    public function isRequestPostLikeMethod( $Request )
    {
        return in_array( $Request->getMethod(), array( 'PUT', 'POST', 'PATCH' ) );        
    }

    protected function save( $entity )
    {
        $this->checkAccessGranted();

        $em = $this->DoctrineM();
        $em->persist( $entity );
        $em->flush();        
    }

    protected function DoctrineM()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function Repo( $entityName )
    {
        return $this->DoctrineM()->getRepository( $entityName );
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