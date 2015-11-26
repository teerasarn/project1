<?php

namespace Five\CuisinesVerdunBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Five\CuisinesVerdunBundle\Entity\User;
use Five\CuisinesVerdunBundle\Form\UserType;

class UserService extends BaseServiceController
{

    protected $repo_name = 'FiveCuisinesVerdunBundle:User';

    public function createFromPost( $type, $prefix, Request $Request, $loc )
    {

        $Form_type       = 'Five\CuisinesVerdunBundle\Form\UserType';
        $em = $this->getDoctrine()->getManager();
        $entity          = new User();
        $errors          = array();
        $form            = $this->createForm( new $Form_type, $entity );

        $first_name      = $Request->request->get( 'f-'.$prefix.'-firstname', null );
        $last_name       = $Request->request->get( 'f-'.$prefix.'-lastname', null );
        $email           = $Request->request->get( 'f-'.$prefix.'-email', null );
        $phone           = $Request->request->get( 'f-'.$prefix.'-telephone', null );
        $postal_code     = $Request->request->get( 'f-'.$prefix.'-cp', null );
        $project         = $Request->request->get( 'f-'.$prefix.'-message', null );
        $options         = array();

        // Disponibilité
        $dispo = array();
        if ($Request->request->get( 'f-'.$prefix.'-day', null )) $dispo[] = "jour";
        if ($Request->request->get( 'f-'.$prefix.'-night', null )) $dispo[] = "nuit";
        if ($Request->request->get( 'f-'.$prefix.'-weekend', null )) $dispo[] = "fin de semaine";

        $branch = intval($Request->request->get( 'f-'.$prefix.'-succursale', -1 ));
        if ($branch == -1) {
            $pc_indicator = strtoupper(substr($postal_code, 0, 3));
            $branch = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findBranch($pc_indicator);
        }

        $branchName = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->getBranchName($branch);

        // Contact Email
        // general messages
        $contactEmail = "info@pfverdun.com";
        // submission requests
        if ($prefix != "mg") {
            $contactEmail = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->getContactEmail($branch);
        }

        $entity
            ->setFirstName( $first_name )
            ->setLastName( $last_name )
            ->setEmail( $email )
            ->setPhone( $phone )
            ->setPostalCode( $postal_code )
            ->setOptions( implode(',',$options) )
            ->setProject( $project )
            ->setIpAddress( $Request->server->get("REMOTE_ADDR") )
            ->setDeviceType( $this->container->get('session')->get("isMobile") ? "mobile" : "desktop" )
            ->setUserAgent( $Request->headers->get('User-Agent') )
            ->setAvailability( implode(',',$dispo) )
            ->setMessageType( $type )
            ->setBranch( $branch )
            ->setSiteLanguage( $loc )
            ->setEnabled( true )
        ;
        $this->save( $entity );

        // Send email based on branch.
        // *** DEBUG.
        // $contactEmail = array("stephen@five.ca");

        switch($prefix) {
            case "mg":
                $subject = "Site web: message general";
                break;
            case "ap":
                $subject = "Site web: appelez-moi";
                break;
            default:
                $subject = "Site web: (INCONNU)";
        }

        // confirmation
        $emailMessage = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('info@cuisinesverdun.com')
            ->setTo($contactEmail)
            ->setBody(
                $this->renderView(
                    'FiveCuisinesVerdunBundle::email.html.twig',
                    array(
                        'name'         => $first_name . " " . $last_name,
                        'email'        => $email,
                        'phone'        => $phone,
                        'postal_code'  => $postal_code,
                        'branch'       => $branchName,
                        'availability' => $dispo,
                        'options'      => $options,
                        'language'     => ($loc == "fr") ? "français" : "anglais",
                        'message'      => $project
                    )
                )
            )
            ->setContentType( 'text/html' )
        ;
        $this->get('mailer')->send($emailMessage);

        if ($email) {
            if ($loc == "en") { $subject = "Cuisines Verdun submission received"; }
            else { $subject = "Cuisines Verdun a bien reçu votre soumission"; }

            $emailMessage = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('info@cuisinesverdun.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'FiveCuisinesVerdunBundle::confirm.email.'.$loc.'.html.twig',
                        array(
                            'branchID'       => $branch
                        )
                    )
                )
                ->setContentType( 'text/html' )
            ;
            $this->get('mailer')->send($emailMessage);
        }
        return $entity;

    }

}
