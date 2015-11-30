<?php

namespace Five\CuisinesVerdunBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RequestListener
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
		$this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if( !$this->container->get( 'session' )->isStarted() )
        {
            $this->container->get( 'session' )->start();
            $dt = new \DateTime();
            $this->container->get( 'logger' )->info( 'Started session ' . $dt->format( 'Y-m-d H:i:s' ) );
        }

    	if(!$this->container->get('session')->has("isMobile")) {
    		$mobileDetector = $this->container->get('mobile_detect.mobile_detector');
    		$this->container->get('session')->set("isMobile", ($mobileDetector->isMobile() || $mobileDetector->isTablet()  ? 1 : 0));
    	}

        //if( $this->container->get('session')->get("isMobile") == 1 )
        //{
        //    $mobile_url = $event->getRequest()->getRequestUri();
        //    if (strstr($mobile_url, "mobile") === FALSE) {
        //        $response   = new RedirectResponse( sprintf( '/%s/mobile', $event->getRequest()->getLocale() ) );
        //        $event->setResponse( $response );
        //    }
        //}

		//if( $this->container->get('session')->get("isMobile") == 1 )
        //{
        //    $mobile_url = $this->_getMobileDomain( $event->getRequest()->getHttpHost() );
        //    $response   = new RedirectResponse( sprintf( 'http://%s/', $mobile_url ) );
        //    $event->setResponse( $response );
			//$event->getRequest()->setRequestFormat('mobi', 'text/html');
		//}
    }

    private function _getMobileDomain( $host )
    {
        switch( mb_strtolower( $host ) )
        {
            case 'poptimize.dev.eis5.com':
                return 'm-poptimize.dev.eis5.com';
            break;
            case 'stage.poptimism.ca':
                return 'm-stage.poptimism.ca';
            break;
            case 'poptimism.ca':
                return 'm.poptimism.ca';
            break;
            case 'poptimize.localhost':
                return 'm.poptimism.ca';
            break;
        }
    }
}

 ?>