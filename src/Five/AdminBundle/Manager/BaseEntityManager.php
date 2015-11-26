<?php

namespace Five\AdminBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class BaseEntityManager
{
    protected $managerRegistry = null;
    protected $entityClass     = null;
    protected $manager         = null;
    #protected $RequestStack    = null;
    protected $classProperties = array();
    protected $reflectionClass = null;
    protected $SecurityContext;

/*    public function __construct( ManagerRegistry $managerRegistry, RequestStack $RequestStack, $entityClass )
    {
        $this->setRequestStack( $RequestStack );
        $this->setManagerRegistry( $managerRegistry );
        $this->setEntityClass( $entityClass );
    }*/

    public function save( $entity, $flush = true )
    {        
        $this->getManager()->persist( $entity );

        if( $flush )
        {
            $this->manager->flush();
        }

        return $entity->getId();
    }

    public function getClassProperties()
    {
        if( empty( $this->classProperties ) )
        {
            $properties = array();
            $objects = $this->getReflectionClass()
                            ->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED )
            ;
            
            foreach( $objects as $object )
            {
                $properties[] = $object->name;
            }

            $this->classProperties = $properties;
        }

        return $this->classProperties;
    }

    public function hasClassProperty( $property )
    {
        return in_array( $property, $this->getClassProperties() );
    }

    public function getReflectionClass()
    {
        if( $this->reflectionClass === null )
        {
            $this->reflectionClass = new \ReflectionClass( new $this->entityClass() );
        }

        return $this->reflectionClass;
    }

    public function setEntityClass( $class )
    {
        $this->entityClass = $class;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setManagerRegistry( ManagerRegistry $managerRegistry )
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getManagerRegistry()
    {
        return $this->managerRegistry;
    }

    public function getManager()
    {
        if( is_null( $this->manager ) )
        {
            $this->setManager();
        }
        
        return $this->manager;
    }

    public function setManager( $managerRegistry = null )
    {
        if( !is_null( $this->managerRegistry ) )
        {
            $this->manager = $this->managerRegistry->getManagerForClass( $this->entityClass );
        }

        return $this;
    }

    public function repo()
    {
        return $this->getManager()->getRepository( $this->entityClass );
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