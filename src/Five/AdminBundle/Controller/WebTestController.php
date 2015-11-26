<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AurayCapital\SiteBundle\Entity\User;
use AurayCapital\SiteBundle\Form\UserType;

class WebTestController extends Controller
{
    public function listAction( $options )
    {
        $params = $this->container->getParameter( $options );

        return $this->get( 'five.form_manager' )->listEntities( 
            $params[ 'entity' ],
            $params
        );
    }


    public function createAction( Request $Request, $options )
    {
        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->create( 
            $this->get( $params[ 'form' ] ),
            $params[ 'entity' ],
            $params
        );        
    }

    public function editAction( Request $Request, $id, $options )
    {
        $params = $this->container->getParameter( $options );
        $params[ 'form_action' ][ 'parameters' ][ 'id' ] = $id;

        return $this->get( 'five.form_manager' )->edit( 

            $this->get( $params[ 'form' ] ),
            $params[ 'entity' ],
            $id,
            $params
        );
    }  
}
