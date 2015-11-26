<?php
 
namespace Five\SeoBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Persistence\ManagerRegistry;

class FiveSeoExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
    private $requestStack;
    private $router;
    protected $managed_locales;
    protected $default_locale;
    protected $managed_locales_name;
    protected $session;


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
        $options = array();

        return array(
            new \Twig_SimpleFunction( 'FiveSeoRenderMetaData',  array( $this, 'fiveSeoRenderMetaData' ) ),
            new \Twig_SimpleFunction( 'FiveSeoRenderTitle',  array( $this, 'fiveSeoRenderTitle' ) )
            //new \Twig_SimpleFunction( 'FiveSeoRenderMeta', array( $this, 'fiveLocaleName' ) ),
        );
    }

    public function fiveSeoRenderMetaData()
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
                return array();
            }

            return $FiveSeoDefault->getMetasName();
        }
        else
        {
            return $FiveSeo->getMetasName();
        }
    }

    public function fiveSeoRenderTitle()
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
    }    

    protected function getLocale( $locale = null )
    {
        if( in_array( $locale, $this->managed_locales ) )
        {
            return $locale;
        }
        
        $currentLocale = $this->getRequest()->getLocale();

        if( $currentLocale != $this->default_locale )
        {
            return $this->default_locale;
        }

        if( $currentLocale === $this->default_locale && in_array( 'fr', $this->managed_locales ) )
        {
            return 'fr';
        }        

        return $this->default_locale;
    }



    public function fiveLocaleUrl( $options = array() )
    {
        $route      = $this->getRequest()->get( '_route' );
        $translated = $this->getRequest()->get( '_translated', array() );

        $locale     = null;

        if( isset( $options[ 'route' ] ) )
        {
            if( $options[ 'route' ] != 'current' )
            {
                $route = $options[ 'route' ];
            }            
        }

        if( isset( $options[ '_locale' ] ) )
        {
            if( in_array( $options[ '_locale' ], $this->managed_locales ) )
            {
                $locale = $options[ '_locale' ];
            }        
        }

        $currentLocale = $this->getRequest()->getLocale();
        $locale        = ( $locale == null ? $this->getLocale() : $locale );
/*        var_dump( $locale);*/
       // var_dump($translated);
        if( isset( $translated[ $locale ] ) )
        {
            return $translated[ $locale ];
        }
        else
        {
/*        var_dump( $locale);
        var_dump($translated); */           
            $params = array_merge( $this->getRequest()->get( '_route_params' ), $options );
            $params[ '_locale' ] = $locale;
            $params[ 'locale' ] = $locale;
            return $this->generateUrl( $route, $params );
        }
    }

    public function fiveLocaleName( $locale = null )
    {
        $currentLocale = $this->getRequest()->getLocale();
        $locale  = $this->getLocale( $locale );

        if( in_array( $currentLocale, array_keys( $this->managed_locales_name ) ) )
        {
            if( in_array( $locale, array_keys( $this->managed_locales_name[ $currentLocale ] ) ) )
            {
                return $this->managed_locales_name[ $currentLocale ][ $locale ];
            }            
        }

        return null;
    }    

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
            $this->manager = $this->managerRegistry->getManagerForClass( $this->entityClass );
        }

        return $this;
    } 


    public function getName()
    {
         return 'FiveSeo';
    }
}
?>