<?php

namespace Five\CVApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MessageController extends ApiAbstractController
{

    public function messageAction( Request $Request, $_locale )
    {

        $Message = $this->get( 'five.userservice' )->createFromPost( "message", "mg", $Request, $_locale );

        if (is_array($Message)) {
            return $this->getJsonResponse( array(
                    'success' => false,
                    'action'  => 'user_submit',
                    'errortype' => 'fields_required',
                    'errors'  => $Message
                )
            );
        } elseif (is_object($Message)) {
            return $this->getJsonResponse( array(
                    'success' => true,
                    'action'  => 'user_submit',
                    'user' => $Message->toArray()
                )
            );
        } else {
            return $this->getJsonResponse( array(
                    'success' => false,
                    'action'  => 'user_submit',
                    'errortype' => 'unknown_error',
                    'errors'  => $Message
                )
            );
        }
    }

    public function appelerAction( Request $Request, $_locale )
    {

        $Message = $this->get( 'five.userservice' )->createFromPost( "appeler", "ap", $Request, $_locale );

        if (is_array($Message)) {
            return $this->getJsonResponse( array(
                    'success' => false,
                    'action'  => 'user_submit',
                    'errortype' => 'fields_required',
                    'errors'  => $Message
                )
            );
        } elseif (is_object($Message)) {
            return $this->getJsonResponse( array(
                    'success' => true,
                    'action'  => 'user_submit',
                    'user' => $Message->toArray()
                )
            );
        } elseif ($Message == "email_used") {
            return $this->getJsonResponse( array(
                    'success' => false,
                    'action'  => 'user_submit',
                    'errortype' => 'email_used',
                    'errors' => array("five_cv_email")
                )
            );
        } else {
            return $this->getJsonResponse( array(
                    'success' => false,
                    'action'  => 'user_submit',
                    'errortype' => 'unknown_error',
                    'errors'  => $Message
                )
            );
        }
    }

    public function branchAction( Request $Request, $_locale, $pc )
    {
        $branchID = $this->get( 'five.branchservice' )->fetchBranch($pc);

        if ($branchID >= 0) {
            return $this->getJsonResponse( array(
                    'success' => true,
                    'action'  => 'fetch_branch',
                    'branch'  => $branchID
                )
            );
        } else {
            return $this->getJsonResponse( array(
                    'success' => false,
                    'action'  => 'fetch_branch',
                    'errortype' => 'not_found'
                )
            );
        }
    }
}
