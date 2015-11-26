<?php
 
namespace Five\AdminBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Persistence\ManagerRegistry;

class FiveEditExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
    private $requestStack;
    private $router;
    protected $managed_locales;
    protected $default_locale;
    protected $managed_locales_name;
    protected $session;

    protected $SecurityContext;
    protected $manager = null;
    protected $managerRegistry = null;
    protected $entityClass     = null;    

    protected $route  = null;
    protected $url    = null;
    protected $entity = null;

    // public function __construct( 
    //     $default_locale             = 'en', 
    //     array $managed_locales      = array( 'en', 'fr' ), 
    //     array $managed_locales_name = array(), $session 
    // )
    // {
    //     $this->default_locale       = $default_locale;
    //     $this->managed_locales      = $managed_locales;
    //     $this->managed_locales_name = $managed_locales_name;
    //     $this->session              = $session;        
    // }

    /**
     *Return the function registered as twig extension
     *
     *@return array
     */
    public function getFunctions()
    {
        $options = array( 'is_safe' => array( 'html' ) );

        return array(
            new \Twig_SimpleFunction( 'FiveEditEntity',  array( $this, 'fiveEdit' ), $options ),
            new \Twig_SimpleFunction( 'FiveEditTrans',  array( $this, 'fiveEditTrans' ), $options ),
            //new \Twig_SimpleFunction( 'FiveSeoRenderTitle',  array( $this, 'fiveSeoRenderTitle' ) )
        );
    }

    public function fiveEdit( $entity, $options, $tagAttrs = array() )
    {
        if( $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' ) )
        {        
            $type = isset( $options[ 'type' ] ) ? $options[ 'type' ] : 'text';
            $property = isset( $options[ 'property' ] ) ? $options[ 'property' ] : 'property';
            $props = array( 
                'data-pk'        => null,
                'data-url'       => null,
                'data-title'     => null,
                'data-type'      => $type,
                'data-five-edit' => 'entity'
            );

            $method = $this->camelize( sprintf( 'get_%s', $property ) );

            if( $entity && method_exists( $entity, $method ) )
            {
                $props = $this->prepareEntityProps( $entity, $property, $type );
                $vars  = $this->buildAttributes( $props );

                return sprintf( '%s', implode( ' ', $vars ) );
            }
        }

        return '';        
    }
    public function fiveEditTrans( $domain, $key, array $options = array() )
    {
        if( $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' ) )
        {
            $type = isset( $options[ 'type' ] ) ? $options[ 'type' ] : 'text';
            $property = $key;
            //isset( $options[ 'property' ] ) ? $options[ 'property' ] : 'property';
            $props = array( 
                'data-pk'        => null,
                'data-url'       => null,
                'data-title'     => null,
                'data-type'      => $type,
                'data-five-edit' => 'trans'
            );

            $props = $this->prepareTransProps( $domain, $property, $type );
            $vars  = $this->buildAttributes( $props );

            return sprintf( '%s', implode( ' ', $vars ) );
        }

        return '';
    }          

    protected function buildAttributes( $props )
    {
        $vars = array();

        foreach( $props as $key => $value )
        {
            $vars[] = sprintf( '%s=\"%s\"', $key, $value );
        }

        return $vars;        
    }

    protected function prepareEntityProps( $entity, $property, $type )
    {
            $props = array( 
                'data-pk'        => $entity->getId(),
                'data-url'       => $this->generateUrl( 'api_five_edit_property', array( 
                    'id'       => $entity->getId(),
                    'property' => $property,
                    '_locale'  => $this->getRequest()->getLocale(),
                    'bundle'   => $this->getBundleName( get_class( $entity ) ),
                    'entity'   => $this->getEntityName( get_class( $entity ) )
                ) ),
                'data-title'     => null,
                'data-type'      => $type,
                'data-five-edit' => 'entity'
            ); 

        return $props;       
    }

    protected function prepareTransProps( $entity, $property, $type )
    {
            $props = array( 
                'data-pk'        => null,
                'data-url'       => $this->generateUrl( 'api_five_edit_translation', array( 
                    'key' => $property,
                    '_locale'  => $this->getRequest()->getLocale(),
                    'domain'   =>  $entity
                ) ),
                'data-title'     => null,
                'data-type'      => $type,
                'data-five-edit' => 'trans'
            ); 

        return $props;       
    }    

    protected function isEntityTranslatable( $entity )
    {
        return ( $entity instanceof \Prezent\Doctrine\Translatable\TranslatableInterface );
    }

    protected function getBundleName( $namespace )
    {
        $expl       = explode( '\\', $namespace );
        $bundleName = '';
        $isLast     = false;

        foreach( $expl as $value )
        {
            if( $value != 'Bundle' && !$isLast )
            {

                if( substr( $value, -6, 6 ) == 'Bundle' )
                {
                    $isLast = true;
                }
                $bundleName .= $value;

                //$bundleCnt++;
            }
        }

        return $bundleName;        
    }

    protected function getEntityName( $entityClass )
    {
        $expl = explode( '\\' , $entityClass );

        return end( $expl );
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


/*    public function fiveSeoRenderTitle()
    {
        $FiveSeo  = $this->getManager()->getRepository( 'FiveSeoBundle:Seo' )->findOneBy( array(
            'locale'  => $this->getRequest()->getLocale(),
            'route'   => $this->getRequest()->get( '_route' ),
            'url'     => $this->getRequest()->getPathInfo(),
            'enabled' => true
        ) );

        if( !$FiveSeo )
        {
            $FiveSeoDefault  = $this->getManager()->getRepository( 'FiveSeoBundle:Seo' )->findOneBy( array(
                'locale'  => $this->getRequest()->getLocale(),
                'route'   => $this->getRequest()->get( '_route' ),
                'enabled' => true
            ) );

            if( !$FiveSeoDefault )
            {
                return '';
            }

            return $FiveSeoDefault->getTitle();
        }
        else
        {
            return $FiveSeo->getTitle();
        }
    }    */

    public function setRequestStack( RequestStack $requestStack )
    {
        $this->requestStack = $requestStack;
    }

    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function setRouter( $router )
    {
        $this->router = $router;
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    } 

/*    public function setRouteTranslationListener( DoctrineExtensionsListener $listener )
    {
        $this->listener = $listener;
    }*/


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
            $this->managerRegistry = $managerRegistry;
            $this->manager = $this->managerRegistry->getManagerForClass( $this->entityClass );
        }

        return $this;
    } 

    public function setSecurityContext( $SecurityContext )
    {
        $this->SecurityContext = $SecurityContext;
    }

    public function getSecurityContext()
    {
        return $this->SecurityContext;
    }

    public function getName()
    {
         return 'FiveEdit';
    }
}
?>