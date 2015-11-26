<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CrudController extends Controller
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

    public function renderAction( $options )
    {        
        $params = $this->container->getParameter( $options );

        return $this->render( $params[ 'template' ], array(            
            'options'  => $params
        ) );
    }

    public function listAction( $options )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );

        return $this->get( 'five.form_manager' )->listEntities( 
            $params
        );
    }
//five_admin_carrousel_gallery_image_list
    public function listRealisationAction( $options )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );
        //$this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( $categories );
        $entities = $this->get( 'doctrine' )->getManager()->getRepository( $params[ 'entity' ] )->findAll();
        $params[ 'entities' ] = $entities;
        return $this->get( 'five.form_manager' )->listEntities( 
            $params
        );
    }    

    public function createAction( Request $Request, $options )
    {
        $this->checkAccessGranted( true );
        $params = $this->container->getParameter( $options );               

        return $this->get( 'five.form_manager' )->create( 
            $this->get( $params[ 'form' ] ),
            $params
        );        
    }

    public function editAction( Request $Request, $id, $options )
    {
        $this->checkAccessGranted( true );
        $params = $this->container->getParameter( $options );

        return $this->get( 'five.form_manager' )->edit(
            $this->get( $params[ 'form' ] ),
            $params,
            $id
        );
    }

    public function angularEditAction( Request $Request, $id, $options )
    {
        $this->checkAccessGranted( true );
        $params = $this->container->getParameter( $options );

        return $this->render('FiveAdminBundle:Action:angular_edit.html.twig', array(
            'options' => $options
        ) );
    }    

/*    public function deleteAction( Request $Request, $id, $options )
    {
        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->deleteAction(
            $this->get( $params[ 'form' ] ),
            $params,
            $id
        );        
    }*/

    public function deleteAction( Request $Request, $id, $options, $entityName )
    {
        $this->checkAccessGranted( true );
        //$options[ 'form_action' ][ 'parameters' ][ 'id' ] = $id;
        //$form = $this->createDeleteForm( $form, $options, $id );

        if( in_array( strtoupper( $this->getRequest()->getMethod() ), array( 'DELETE', 'POST' ) ) )
        {
            //$form->handleRequest( $this->getRequest() );
    
            //if( $form->isValid() )
            //{
                
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository( $entityName )->find( $id );

                if( $entity )
                {
                    $em->remove( $entity );
                    $em->flush();

                    return new JsonResponse( array(
                        'status' => 'success',
                        'action' => 'delete',
                        'entityName' => $entityName,
                        'id' => $id
                    ) );
                }

                    return new JsonResponse( array(
                        'status' => 'error',
                        'action' => 'delete',
                        'entityName' => $entityName,
                        'id' => $id
                    ) );                
    /*            if (!$entity) {
                    throw $this->createNotFoundException('Unable to find User entity.');
                }*/

                //return $this->handleSuccessRedirect( $options );
            //}
        }
    }

    public function deleteEntityAction( Request $Request, $id, $entityName )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->deleteEntityAction(
            $this->get( $params[ 'form' ] ),
            $params,
            $id
        );        
    }    

/*    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('seo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }*/

    public function uploadPicturesAction( Request $Request )
    {
        $this->checkAccessGranted( true );

        $file = $Request->files->get( 'file', null );

        if( $file != null )
        {
            $Picture = new \Five\AdminBundle\Entity\Picture();
            $Picture->setFile( $file );

            $em = $this->getDoctrine()->getManager();
            $em->persist( $Picture );
            $em->flush();

            if( $Picture->getId() != null )
            {
                $Picture->generateThumb();

                return new JsonResponse( array( 
                    'status'  => 'success',
                    'picture' => $Picture->getName(),
                    'src'     => $Picture->getWebPath(),
                    'src_thumb'     => $Picture->getThumbWebPath(),
                ) );
            }
        }

        return new JsonResponse( array( 
            'status' => 'error',
            'picture' => 0
        ) );        
    }
}
