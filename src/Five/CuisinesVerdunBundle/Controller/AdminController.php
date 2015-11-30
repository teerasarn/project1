<?php

namespace Five\CuisinesVerdunBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Five\CuisinesVerdunBundle\Entity\User;
use Five\CuisinesVerdunBundle\Form\UserType;

/**
 * Admin controller.
 *
 */
class AdminController extends Controller
{

    public function homeAction(Request $request)
    {
        return $this->render('FiveCuisinesVerdunBundle:Admin:home.html.twig', array(
        ));
    }

    public function infoAction(Request $request)
    {
        return $this->render('FiveCuisinesVerdunBundle:Admin:info.html.twig', array(
            'info' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:User')->fetchMessageData( ),
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches("fr")
        ));
    }

    public function carrouselsAction(Request $request)
    {
        return $this->render('FiveAdminBundle:Action:edit_carrousel.html.twig', array(
        //return $this->render('FiveCuisinesVerdunBundle:Admin:carrousels.html.twig', array(
            'stylesImages' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getImages(),
            'materialsImages' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getImages(),
            'kitchenStyles' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getList('kitchen'),
            'kitchenMaterials' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getList('kitchen'),
            'bathroomStyles' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getList('bathroom'),
            'bathroomMaterials' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getList('bathroom'),
        ));
    }

    public function apiAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            switch ($request->request->get('method')) {

                case 'add-style':
                    $en = $request->request->get('en');
                    $fr = $request->request->get('fr');
                    $type = $request->request->get('type');
                    $frDesc = $request->request->get('frDesc');
                    $enDesc = $request->request->get('enDesc');
                    $id = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->add( $fr, $en, $type, $frDesc, $enDesc );
                    return new JsonResponse(array('success' => true, 'en' => $en, 'fr' => $fr, 'id'=> $id));

                case 'add-material':
                    $en = $request->request->get('en');
                    $fr = $request->request->get('fr');
                    $type = $request->request->get('type');
                    $frDesc = $request->request->get('frDesc');
                    $enDesc = $request->request->get('enDesc');
                    $id = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->add( $fr, $en, $type, $frDesc, $enDesc );
                    return new JsonResponse(array('success' => true, 'en' => $en, 'fr' => $fr, 'id'=>300));

                case 'delete':
                    $id = $request->request->get('id');
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->delete( $id );
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->delete( $id );
                    return new JsonResponse(array('success' => true, 'id' => $id));

                case 'upload-img':
                    $category = $request->request->get('category');
                    $type = $request->request->get('type');
                    $url = $request->request->get('url');
                    $en = $request->request->get('en');
                    $fr = $request->request->get('fr');
                    $id = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Image')->add( $category, $url, $type, $fr, $en );
                    return new JsonResponse(array('success' => true, 'id' => $id));

                case 'delete-img':
                    $id = $request->request->get('id');
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Image')->delete( $id );
                    return new JsonResponse(array('success' => true, 'id' => $id));

                case 'update-img':
                    $id = $request->request->get('id');
                    $en = $request->request->get('en');
                    $fr = $request->request->get('fr');
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Image')->update( $id, $fr, $en );
                    return new JsonResponse(array('success' => true));

                case 'update-order':
                    $ids = $request->request->get('ids');
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Image')->sort( $ids );
                    return new JsonResponse(array('success' => true));

                case 'edit-style':
                    $id = $request->request->get('id');
                    $fr = $request->request->get('fr');
                    $en = $request->request->get('en');
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->edit( $id, $fr, $en );
                    return new JsonResponse(array('success' => true));

                case 'edit-material':
                    $id = $request->request->get('id');
                    $fr = $request->request->get('fr');
                    $en = $request->request->get('en');
                    $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->edit( $id, $fr, $en );
                    return new JsonResponse(array('success' => true));

            }

            // image upload
            if (sizeof($_FILES) > 0) {
                $file = $_FILES[0];
                if (!move_uploaded_file($file['tmp_name'], __DIR__ . "/../../../../web/uploads/".basename($file['name']))) {
                    return new JsonResponse(array('success' => false, 'error' => 'uploaderror'));
                }
                return new JsonResponse(array('success' => true, 'url' => basename($file['name'])));
            }

            return new JsonResponse(array('success' => false, 'error' => 'nomatch'));
        }
        return new JsonResponse(array('success' => false));
    }
}
