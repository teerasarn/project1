<?php
namespace Blogger\BlogBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityLocaleInjector
{
    private $request;
    private $container;

    public function __construct( ContainerInterface $Container )
    {
        $this->container = $Container;
        /*$this->request = $Conatiner->get( 'request' );*/
    }

    // See Doctrine PostLoad lifecycleEvent
    // Each object that has lifecycle enabled will
    // trigger this method right after the object is loaded
    public function postLoad( LifecycleEventArgs $args )
    {
        $entity = $args->getEntity();

        if( in_array( 'Blogger\BlogBundle\Entity\EntityLocaleInjectorInterface', class_implements( $entity ) ) )
        {
            $lang = $this->container->get( 'request' )->getLocale();

            $entity->setEntityLocale( $lang );
        }
    }
}