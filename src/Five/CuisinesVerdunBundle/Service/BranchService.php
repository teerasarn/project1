<?php

namespace Five\CuisinesVerdunBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Five\CuisinesVerdunBundle\Entity\User;
use Five\CuisinesVerdunBundle\Form\UserType;

class BranchService extends BaseServiceController
{

    public function fetchBranch( $pc )
    {
        return $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findBranch($pc);
    }

}