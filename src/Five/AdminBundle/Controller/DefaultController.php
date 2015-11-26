<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('FiveAdminBundle:Default:dashboard-links.html.twig', array(
            'contentHeader' => array( 'title' => 'My title here', 'subtitle' => 'My subtitle' ),
            'countriesCnt' => 0,
            'programsCnt' => 0,
            'contactsCnt' => 0
        ) );
    }
    public function infoAction() {
        return $this->render('FiveCuisinesVerdunBundle:Admin:info.html.twig', array(
            'info' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:User')->fetchMessageData()
        ));
    }

    public function loginPageAction()
    {

    }

    public function galleryListboxAction()
    {
        //$Categories = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array('enabled' => true ), array( 'parent' => 'ASC', 'position' => 'ASC' ) );

        //Galleries,

        return $this->render('FiveAdminBundle:Action:listbox.html.twig', array(
            'contentHeader' => array( 'title' => 'My title here', 'subtitle' => 'My subtitle' ),
            'categories' => $Categories,
            'countriesCnt' => 0,
            'programsCnt' => 0,
            'contactsCnt' => 0
        ) );
    }
}
