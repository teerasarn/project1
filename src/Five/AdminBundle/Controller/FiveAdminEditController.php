<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FiveAdminEditController extends FOSRestController {

  protected $repoName = '';

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  description="Returns all programs per country code with programs links",
     *  filters={
     *      {"name"="_locale", "dataType"="string", "pattern"="(en|fr)"}
     *  }
     * ) 
    */
    public function propertyUpdateAction( Request $request, $bundle, $entity, $id, $property, $_locale = "en" )
    {
        $this->checkAccessGranted( true );
        
        if( $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' )  )
        {
            $repoName = sprintf( '%s:%s', $bundle, $entity );
            $request->setLocale( $request->request->get( '_locale' ) );
            $_locale = $request->request->get( '_locale' );

            $em        = $this->getDoctrine()->getManager();
            $Entity = $em->getRepository( $repoName )->find( $id );

            $method = $this->camelize( sprintf( 'set_%s', $property ) );

            if( $Entity && method_exists( $Entity, $method ) )
            {
                $Entity->$method( $request->request->get( 'value' ) );
                $em->persist( $Entity );
                $em->flush();
            }
            

            return new JsonResponse( array( 'status' => 'success'  ) );            
        }

        return new RedirectResponse( '/' );
    }

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

  public function camelize($scored) {
    return lcfirst(
      implode(
        '',
        array_map(
          'ucfirst',
          array_map(
            'strtolower',
            explode(
              '_', $scored)))));
  }

}
