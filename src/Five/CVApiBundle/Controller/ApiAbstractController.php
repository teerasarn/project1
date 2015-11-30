<?php

namespace Five\CVApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiAbstractController extends Controller
{

    public function getJsonResponse( $data, $status = 200, $headers = array() )
    {
        $response = new JsonResponse( $data, $status, $headers );

        return $response;
    }

    public function save( $entity = null )
    {
        $em = $this->getDoctrine()->getManager();

        if( $entity != null )
        {
            $em->persist( $entity );
        }

        $em->flush();
    }

    public function getEntityAttributeJSON( $entity, $attribute )
    {
        $attribute  = strtolower( $attribute );
        $json       = array(
            'response'  => array(
                'attribute' => array(
                    $attribute => $this->getEntityAttribute( $entity, $attribute )
                )
            )
        );

        return json_encode( $json );
    }

    public function getEntityById( $entity_name, $id )
    {
        $em        = $this->getDoctrine()->getManager();
        $entity    = $em->getRepository( $entity_name )->find( $id );

        if( !$entity )
        {
            throw $this->createNotFoundException( sprintf( 'Unable to find entity %s', $entity_name ) );
        }
        return $entity;
    }
    public function getEntityRepo( $entity_name )
    {
        $em      = $this->getDoctrine()->getManager();
        $repo    = $em->getRepository( $entity_name );

        if( !$repo )
        {
            throw $this->createNotFoundException( sprintf( 'Unable to find entity %s', $entity_name ) );
        }
        return $repo;
    }

    public function getRepo( $name )
    {
        return $this->getEntityRepo( $name );
    }

    public function getEntityJSON( $entity )
    {
        $json = array(
            'response'  => array(
                'entity' => $entity->toArray()
            )
        );

        return json_encode( $json );
    }

    public function getJSONObject( $data )
    {
        $json = array(
            'response'  => array(
                $data
            )
        );

        return json_encode( $json );
    }

    public function getEntityAttribute( $entity, $attribute )
    {
        $data = $entity->toArray();

        if( !in_array( $attribute, array_keys( $data ) ) )
        {
            throw $this->createNotFoundException( sprintf( 'Unable to find attribute %s', $attribute ) );
        }
        return $data[ $attribute ];
    }

    public function getTemplateJSON( $template, array $tpl_args = array(), $args = null )
    {

        $response = array(
            'response'=>array_merge(
                            array('html' => $this->renderView($template, $tpl_args)),
                            (is_null($args) ? array() : $args)
            )
        );

        return json_encode($response);
    }

    public function getJSON( array $params )
    {
        $response = array(
            'response'=>array_merge( ( is_null( $params ) ? array() : $params )
            )
        );

        return json_encode( $response );
    }

    public function getJSONFromArray( Array $list )
    {
        $col_temp = array();
        foreach ($list as $object) {
            $col_temp[] = $object->toArray();
        }

        $response = array(
            'response'=>array_merge( $col_temp )
        );

        return json_encode( $response );
    }

    public function getSuccessResponseJSON( $attribute, $success )
    {

        $response = array(
            'response' => array(
                $attribute => array(
                    'success' => ( bool ) $success
                )
            )
        );

        return json_encode( $response );
    }
}