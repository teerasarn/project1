<?php

namespace Five\CuisinesVerdunBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractServiceController extends Controller
{
    protected $context;
    protected $doctrine;
    protected $em;
    protected $session;
    protected $container;
    protected $translator;
    protected $translation_file;
    protected $repo_name;
    protected $request;

    public function getSession()
    {
        return $this->container->get( 'session' );
    }

    public function getRequest()
    {
        return $this->container->get( 'request' );
    }

    public function getLocale()
    {
        return $this->container->get( 'request' )->getLocale();
    }

    public function getRouter()
    {
        return $this->container->get( 'router' );
    }

    public function generateUrl( $name, $parameters = array(), $absolute = false )
    {
        return $this->container->get( 'router' )->generate( $name, $parameters, $absolute );
    }

    public function setDefaultTranslationFile( $file='messages' )
    {
        $this->translation_file = $file;

        return $this;
    }

    public function getRepo( $repo = null )
    {
        $repo = $repo == null ? $this->repo_name : $repo;

        return $this->em->getRepository( $repo );
    }

    public function repo( $repo = null )
    {
        $repo = $repo == null ? $this->repo_name : $repo;

        return $this->getRepo( $repo );
    }

    protected function _saveEntity( $entity )
    {
        $this->em->persist( $entity );
        $this->em->flush();

        return $this;
    }

    public function save( $entity )
    {
        return $this->_saveEntity( $entity );
    }

    public function em()
    {
        return $this->em;
    }

    public function doctrine()
    {
        return $this->doctrine;
    }

    public function setRepoName( $name )
    {
        $this->repo_name = $name;

        return $this;
    }

    public function getRepoName()
    {
        return $this->repo_name;
    }

    public function getFormErrorMessages(  $form, $template = 'validators' )
    {
        $errors = array();

        foreach( $form->getErrors() as $key => $error )
        {
            $template   = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach( $parameters as $var => $value )
            {
                $template = str_replace( $var, $value, $template );
            }

            $errors[ $key ] = $template;
        }

        if( $form->count() )
        {
            foreach( $form as $child )
            {
                if( !$child->isValid() )
                {
                    $messages = $this->getFormErrorMessages( $child );

                    foreach( $messages as $key => $value )
                    {
                        $messages[ $key ] = $this->translator->trans( $value, array(), $template );
                    }

                    $errors[ $child->getName() ] = $messages[ 0 ];
                }
            }
        }

        return $errors;
    }

    public function getFormErrorFields( $form )
    {
        $errors = array();

        foreach( $form as $child )
        {
            if (!$child->isValid()) {
                $vars = $child->createView()->getVars();
                array_push($errors, $vars["name"]);
            }
        }

        return $errors;
    }

    public function getConstraintsErrorMessages(  $Constraints, $template = 'validators' )
    {
        $errors = array();

        foreach( $Constraints as $Constraint )
        {
            $template   = $Constraint->getMessageTemplate();
            $parameters = $Constraint->getMessageParameters();

            foreach( $parameters as $var => $value )
            {
                $template = str_replace( $var, $value, $template );
            }

            $errors[ $Constraint->getPropertyPath() ] = $Constraint->getMessage();
        }

        return $errors;
    }
}
