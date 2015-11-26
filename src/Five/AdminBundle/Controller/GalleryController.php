<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Five\CuisinesVerdunBundle\Entity\GalleryImage;
use Five\CuisinesVerdunBundle\Entity\GalleryCategory;
use Five\CuisinesVerdunBundle\Entity\Gallery;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GalleryController extends Controller
{

    public function checkAccessGranted( $redirectLogin = false )
    {
        if( false === $this->getSecurityContext()->isGranted( 'ROLE_ADMIN' ) )
        {
            throw new AccessDeniedException();

            if( $redirectLogin )
            {
                return new RedirectResponse( $this->generateUrl( 'five_admin_home' ) );
                exit();
            }
            else
            {
                throw new AccessDeniedException();
            }
        }        
    }

    public function getSecurityContext()
    {
        return $this->container->get( 'security.context' );
    }

    public function updateOrderAction(Request $Request )
    {
/*        if( $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' )  )
        {   */

            $this->checkAccessGranted( true );     

            $doctrineManager = $this->get( 'doctrine' )->getManager();
            $ids = $Request->request->get( 'ids' );
            $ids = explode(",", $ids);
            $starting_index = $Request->request->get( 'index' );

            foreach ($ids as $ite => $id) {
                $gallery = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->find( $id );
                $gallery->setPosition($ite + $starting_index);
                
                $doctrineManager->persist( $gallery );
                $doctrineManager->flush();
            }

            return new JsonResponse( array( 
                'status' => 'success'
            ) ); 
        //}
    }

    public function createAction( $options, $context = 'realisation' )
    {
/*        if( $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' )  )
        {        */
            $this->checkAccessGranted( true );

            $params     = $this->container->getParameter( $options );
            
            //  $parent     = 'room';
            //  $categories = array( 'bathroom' );
            //$context    = 'realisation';
            //$pictures = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( $categories, $context, $parent );

            $MainCategories = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array( 'context' => $context ) );

            $pictures = array();
            return $this->render( 'FiveAdminBundle:Action:edit_gallery.html.twig', array(            
                'options'     => $params,
                'entities'    => $pictures,
                'pictures'    => $pictures,
                'form'        => $this->get( $params[ 'form' ] )->createView(),
                'form_action' => $params[ 'form_action' ],
                'MainCategories' => $MainCategories
            ) );
        //}
    }

    public function editAction( $context = 'realisation', $id, $options )    
    {
/*        if( $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' )  )
        {*/
        $this->checkAccessGranted( true );

            $params  = $this->container->getParameter( $options );        
            $gallery = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->find( $id );
            
            if( $gallery )
            {
                $context = $gallery->getContext();
            }
            else
            {
                $gallery = new Gallery();
            }

            $MainCategories = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array( 'context' => $context ) );


            $dropdowns = array();

            foreach ($MainCategories as $MainCat)
            {
                if( !isset( $dropdowns[ $MainCat->getParent() ] ) )
                {
                    $dropdowns[ $MainCat->getParent() ] = array();
                }

                $dropdowns[ $MainCat->getParent() ][] = array( 'label' => $MainCat->getTitleEn(), 'value' => $MainCat->getName(), 'id' => $MainCat->getId() );
            }

            $pictures   = $gallery->getImages();
            $categories = $gallery->getAllCategoriesNameList();

            return $this->render( 'FiveAdminBundle:Action:edit_gallery.html.twig', array(            
                'options'            => $gallery->getImages(),
                'entities'           => $gallery->getImages(),
                'pictures'           => $pictures,
                'form'               => $this->get( $params[ 'form' ] )->createView(),
                'form_action'        => $params[ 'form_action' ],
                'context'            => $context,
                'gallery'            => $gallery,
                'id'                 => $id,
                'categories'         => $gallery->getAllCategoriesNameList(),
                'dropdownCategories' => $dropdowns

            ) );
        //}
    }

    public function updateAction( Request $Request )
    {
/*        if( $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' )  )
        {  */      
            $this->checkAccessGranted( true );

            $data       = $Request->request->get( 'data' );
            $categories = $data[ 'categories' ];
            $context    = $data['context'];
            
            $cats       = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->getByNames( $categories, $data[ 'context' ] );
            
            $Gallery    = false;

            if( $data[ 'gallery' ][ 'id' ] != '' && !empty( $data[ 'gallery' ][ 'id' ] ) && is_numeric($data[ 'gallery' ][ 'id' ]) )
            {
                $Gallery = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->find( $data[ 'gallery' ][ 'id' ] );
            }        

            if( !$Gallery )
            {
                $Gallery     = new Gallery();
                $GalleryLast = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->findOneBy( array( 'context' => $context ), array( 'position' => 'DESC' ) );

                $Gallery->setPosition( -1 );

                if( method_exists( $GalleryLast, 'getId' ) )
                {
                    if( is_numeric( $GalleryLast->getId() ) )
                    {
                        $position = ( ( int ) $GalleryLast->getId() + 1 );
                        $Gallery->setPosition( $position );
                    }
                }            

                if( method_exists( $GalleryLast, 'getPosition' ) )
                {
                    if( is_numeric( $GalleryLast->getPosition() ) )
                    {
                        $position = ( ( int ) $GalleryLast->getPosition() + 1 );
                        $Gallery->setPosition( $position );
                    }
                }



                //var_dump($GalleriesList->getPosition());
            }
    /*        else
            {
                $Gallery->setPosition( ( int ) $newPic[ 'position' ] );
            }*/

            if( $Gallery )
            {
                $catsRemove = $Gallery->getCategories();
                $Gallery->removeCategories( $catsRemove );
                $Gallery->setCategories( $cats );

                $Gallery->setTitleFr( $data[ 'gallery' ][  'titleFr' ] );
                $Gallery->setTitleEn( $data[ 'gallery' ][  'titleEn' ] );
                $Gallery->setDescriptionFr( $data[ 'gallery' ][  'descFr' ] );
                $Gallery->setDescriptionEn( $data[ 'gallery' ][  'descEn' ] );

                if( isset( $data[ 'tags' ] ) )
                {
                    $Gallery->setTags( $data[ 'tags' ] );
                }
                
                $beforePictures = $Gallery->getImages();
                $ids            = array();

                if( isset( $data[ 'pictures' ] ) )
                {
                    $coverSet = false;

                    foreach( $data[ 'pictures' ] as $newPic )
                    {
                        if( $newPic[ 'id' ] != '' && is_numeric( $newPic[ 'id' ] ) )
                        {                
                            $PicEntity = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->find( $newPic[ 'id' ] );

                            if( !$PicEntity )
                            {
                                $PicEntity = new GalleryImage();
                            }
                            else
                            {
                                $ids[] = $newPic[ 'id' ];
                            }
                        }
                        else
                        {
                            $PicEntity = new GalleryImage();
                        }                    

                        $PicEntity->setSrc( $newPic[ 'src' ] );
                        $PicEntity->setSrcThumb( $newPic[ 'srcThumb' ] );
                        $PicEntity->setTitleFr( $newPic[ 'titleFr' ] );
                        $PicEntity->setTitleEn( $newPic[ 'titleEn' ] );
                        $PicEntity->setDescriptionFr( $newPic[ 'descFr' ] );
                        $PicEntity->setDescriptionEn( $newPic[ 'descEn' ] );
                        

                        if( ( $newPic[ 'cover' ] == true || $newPic[ 'cover' ] == 1 ) && $coverSet == false )
                        {
                            $PicEntity->setCover( true );    
                            $Gallery->setCover( $PicEntity->getSrc() );
                            $coverSet = true;
                        }
                        else
                        {
                            $PicEntity->setCover( false );
                        }

                        $Gallery->addImage( $PicEntity );
                    }
                }

                foreach( $beforePictures as $oldPic )
                {
                    if( !in_array( $oldPic->getId(), $ids ) && $oldPic->getId() != null )
                    {
                        $oldEntity = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->find( $oldPic->getId() );

                        if( $oldEntity )
                        {
                            $Gallery->removeImage($oldEntity);
                            
                        }                    
                    }
                }

                if( $Gallery->getCover() == '/bundles/fiveadmin/img/no-image.png' || $Gallery->getCover() == '' || $Gallery->getCover() == null )
                {
                    if( $Gallery->getImages()->count() > 0 )
                    {
                        $Gallery->getImages()->first()->setCover(true);
                        $Gallery->setCover( $Gallery->getImages()->first()->getSrc() );
                    }
                    else
                    {
                        $Gallery->setCover( '/bundles/fiveadmin/img/no-image.png' );
                    }
                }

                $this->get( 'doctrine' )->getManager()->persist( $Gallery );    

                $this->get( 'doctrine' )->getManager()->flush();            

                return new JsonResponse( $data );            
            }

            return new JsonResponse( $data );
        //}
    }    

    //private function 

    public function publishAction( Request $Request, $id, $enabled )
    {
        $this->checkAccessGranted( true );

        $em      = $this->get( 'doctrine' )->getManager();
        
        $checked = false;

        if( $enabled == 'true' )
        {
            $checked = true;
        }

        //$enabled = ( $enabled === true ? true : false );

        if( is_numeric( $id ) )
        {
            $Gallery  = $em->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->find( $id );

            if( $Gallery )
            {
                $Gallery->setEnabled( $checked );
                $em->persist( $Gallery );
                $em->flush();

                return new JsonResponse( array(
                    'status' => 'success',
                    'entity' => array(
                        'id'      => $id,
                        'enabled' => $checked ? 'true' : 'false'
                    )
                ) );
            }
        }

        return new JsonResponse( array(
            'status' => 'error',
            'entity' => array(
                'id'      => $id,
                'enabled' => $checked ? 'true' : 'false'
            )
        ) );        

    }

    public function listAction( Request $Request, $context = 'realisation', $options )
    {
        $this->checkAccessGranted( true );

        $queryParent = $Request->query->get( 'parent', null );
        $queryCat    = $Request->query->get( 'category', null );

        $params = $this->container->getParameter( $options );
        /*$entities = $this->get( 'doctrine' )->getManager()->getRepository( $params[ 'entity' ] )->findAll();*/
        $galleries  = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->findBy( array( 'context' => $context ) );

        // All categories for this context
        $categories = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array( 'context' => $context ), array( 'parent' => 'ASC', 'position' => 'ASC' ) );

        $filtersOptions = array();
        $curParent      = null;        
        $parentList     = array();


        // Filtered Categories
        $FilterSearch = array( 'context' => $context );
        if( $queryParent != null && !empty( $queryParent ) )
        {
            $FilterSearch[ 'parent' ] = $queryParent;
        }

        if( $queryCat != null && !empty( $queryCat ) )
        {
            $FilterSearch[ 'name' ] = $queryCat;
        }        

        $FilteredCategories = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:GalleryCategory')->findBy( $FilterSearch, array( 'parent' => 'ASC', 'position' => 'ASC' ) );
        $filteredCatIds = array();

        foreach ($FilteredCategories as $filteredCat )
        {
            $filteredCatIds[] = $filteredCat->getId();
        }
        
        $FilteredGalleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $filteredCatIds, $context );

        $params[ 'entities' ]   = $FilteredGalleries;
        $params[ 'categories' ] = $categories;


        $params[ 'context' ] = $context;
        $params[ 'filters' ] = true;
        /*$params[ 'filters' ] = true;*/

        $params[ 'options' ] = $params;
        
        $labels = $FilteredGalleries[0]->getAllCategoriesLabelList(true);
        $exclude = array( 'room', 'style' );

        return $this->get( 'five.form_manager' )->listEntities( 
            $params
        );
    }
//five_admin_carrousel_gallery_image_list
    public function listRealisationAction( $options )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );
        //$this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( $categories );
        $entities = $this->get( 'doctrine' )->getManager()->getRepository( $params[ 'entity' ] )->findAll();
        $params[ 'entities' ] = $entities;
        return $this->get( 'five.form_manager' )->listEntities( 
            $params
        );
    }    

    public function angularEditAction( Request $Request, $id, $options )
    {
        $params = $this->container->getParameter( $options );

        return $this->render('FiveAdminBundle:Action:angular_edit.html.twig', array(
            'options' => $options
        ) );
    }    

/*    public function deleteAction( Request $Request, $id, $options )
    {
        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->deleteAction(
            $this->get( $params[ 'form' ] ),
            $params,
            $id
        );        
    }*/

    public function deleteAction( Request $Request, $id, $options, $entityName )
    {

        $this->checkAccessGranted( true );

        //$options[ 'form_action' ][ 'parameters' ][ 'id' ] = $id;
        //$form = $this->createDeleteForm( $form, $options, $id );

        if( in_array( strtoupper( $this->getRequest()->getMethod() ), array( 'DELETE', 'POST' ) ) )
        {
            //$form->handleRequest( $this->getRequest() );
    
            //if( $form->isValid() )
            //{
                
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository( 'FiveCuisinesVerdunBundle:Gallery' )->find( $id );

                if( $entity )
                {
                    foreach ($entity->getImages() as $image)
                    {
                        $em->remove( $image );
                    }
                    $EntityCategories = $entity->getCategories();
                    $entity->removeCategories( $EntityCategories );

                    $em->remove( $entity );
                    $em->flush();

                    return new JsonResponse( array(
                        'status' => 'success',
                        'action' => 'delete',
                        'entityName' => $entityName,
                        'id' => $id
                    ) );
                }

                    return new JsonResponse( array(
                        'status' => 'error',
                        'action' => 'delete',
                        'entityName' => $entityName,
                        'id' => $id
                    ) );                
    /*            if (!$entity) {
                    throw $this->createNotFoundException('Unable to find User entity.');
                }*/

                //return $this->handleSuccessRedirect( $options );
            //}
        }
    }

    public function deleteEntityAction( Request $Request, $id, $entityName )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );       

        return $this->get( 'five.form_manager' )->deleteEntityAction(
            $this->get( $params[ 'form' ] ),
            $params,
            $id
        );        
    }    

/*    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('seo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }*/

    public function uploadPicturesAction( Request $Request )
    {
        $this->checkAccessGranted( true );
        
        $file = $Request->files->get( 'file', null );

        if( $file != null )
        {
            $Picture = new \Five\AdminBundle\Entity\Picture();
            $Picture->setFile( $file );

            $em = $this->getDoctrine()->getManager();
            $em->persist( $Picture );
            $em->flush();

            if( $Picture->getId() != null )
            {
                $Picture->generateThumb();

                return new JsonResponse( array( 
                    'status'  => 'success',
                    'picture' => $Picture->getName(),
                    'src'     => $Picture->getWebPath(),
                    'src_thumb'     => $Picture->getThumbWebPath(),
                ) );
            }
        }

        return new JsonResponse( array( 
            'status' => 'error',
            'picture' => 0
        ) );        
    }
}
