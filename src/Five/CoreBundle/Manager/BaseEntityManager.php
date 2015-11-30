<?php

namespace Five\CoreBundle\Manager;

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

    // public function setClassPropertiesValue( array $propertiesValue = array() )
    // {
    //     $classProperties = $this->getClassProperties();

    //     foreach( $classProperties as $property )
    //     {
    //         if( isset( $propertiesValue[ $property ] ) )
    //         {
    //             $expl    = explode( '_', $property );
    //             $keys    = ( is_array( $expl ) ? implode( array_map( 'ucfirst', $expl ) ) : $expl );
    //             $method  = sprintf( 'set%s', ucfirst( $keys ) );

    //             if( $this->hasClassMethod( $method ) )
    //             {
    //                 $this->$method( $propertiesValue[ $property ] );
    //             }
    //         }
    //     }

    //     return $this;
    // }

    // public function hasClassMethod( $method )
    // {
    //     return method_exists( new $this->entityClass(), $method );
    // }    

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

    // public function setRequestStack( RequestStack $RequestStack )
    // {
    //     $this->RequestStack = $RequestStack;
    // }

    // public function getRequest()
    // {
    //     return $this->RequestStack->getCurrentRequest();
    // }    
}