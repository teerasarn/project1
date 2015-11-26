<?php

namespace Five\MailerBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class BaseEntityManager
{
    protected $managerRegistry;
    protected $entityClass;
    protected $manager;
    protected $RequestStack;
    protected $classProperties = array();

    protected $reflectionClass = null;

    public function __construct( ManagerRegistry $managerRegistry, RequestStack $RequestStack, $entityClass )
    {
        $this->setRequestStack( $RequestStack );
        $this->setManagerRegistry( $managerRegistry );
        $this->setEntityClass( $entityClass );
    }

    public function save( $entity, $flush = true )
    {
        $manager = $this->managerRegistry->getManagerForClass( $this->entityClass );
        $manager->persist( $entity );

        if( $flush )
        {
            $manager->flush();
        }
    }

    public function getClassProperties()
    {
        if( empty( $this->classProperties ) )
        {
            return $this->getReflectionClass()->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED );
        }

        return $this->classProperties;
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

    public function setManagerRegistry( ManagerRegistry $managerRegistry )
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function setRequestStack( RequestStack $RequestStack )
    {
        $this->RequestStack = $RequestStack;
    }

    public function getRequest()
    {
        return $this->RequestStack->getCurrentRequest();
    }    
}