<?php

namespace Five\CuisinesVerdunBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Five\CuisinesVerdunBundle\Service\AbstractServiceController;

class BaseServiceController extends AbstractServiceController
{

    public function __construct( $context, $doctrine, $session, $container )
    {
        $this->context    = $context;
        $this->doctrine   = $doctrine;
        $this->em         = $doctrine->getManager();
        $this->session    = $session;
        $this->container  = $container;
    }
}
