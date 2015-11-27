<?php

namespace Five\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Blogger\BlogBundle\Entity\Blog;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class BlogController extends Controller
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

	public function uploadPicturesAction( Request $Request )
    {

        $this->checkAccessGranted( true );

        $file = $Request->files->get( 'file', null );


        if( $file != null )
        {
            //$Picture = new \Five\AdminBundle\Entity\Picture();
            //$Picture->setFile( $file );
				//$Picture->setPath('blog');
				
	
				// no database issue here
            //$em = $this->getDoctrine()->getManager();
            //$em->persist( $Picture );
            //$em->flush();
				//$Picture->preUpload();
				//add new overide function include argument for images path
				//$Picture->upload('blog');
				//print_r($file);exit();
				
				//=== pre upload ===
            $filename = sha1(uniqid(mt_rand(), true));
            $extension = str_replace( 'jpeg', 'jpg', strtolower( $file->guessExtension() ) );
            //$path = $filename.'.'. $extension;
            $name = $filename.'.'.$extension;
            $hash = $filename;
            $size = $file->getClientSize();
            $MimeType = mb_strtolower( $file->getMimeType() ) ;				
				
				$path =  __DIR__.'/../../../../web/uploads/blog';
				$path_thumb =  __DIR__.'/../../../../web/uploads/blog/thumbs';
				$file->move($path,$name);

            //if( $Picture->getId() != null )
            //{
            	 //remove thumb generate
                //$Picture->generateThumb();

                return new JsonResponse( array( 
                    'status'  => 'success',
                    'picture' => $name,
                    'src'     => '/uploads/blog/'.$name ,
                    'src_thumb'     => '/uploads/blog/thumbs/'.$name ,

                ) );
            //}
        }

        return new JsonResponse( array( 
            'status' => 'error',
            'picture' => 0
        ) );        
    }


    public function createAction( $options, $context = 'realisation' )
    {

            $this->checkAccessGranted( true );

            $params     = $this->container->getParameter( $options );

            //  $parent     = 'room';
            //  $categories = array( 'bathroom' );
            //$context    = 'realisation';
            //$pictures = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( $categories, $context, $parent );


            $pictures = array();
            return $this->render( 'FiveAdminBundle:Action:edit_blog.html.twig', array(            
                'options'     => $params,
					 'entities'    => $pictures,
                'form'        => $this->get( $params[ 'form' ] )->createView(),
                'form_action' => $params[ 'form_action' ],
                'blog'     =>  new Blog()
            ) );
        //}
    }
  
    public function editAction($id, $options )    
    {
			  
            $this->checkAccessGranted( true );

            $params  = $this->container->getParameter( $options );        
            $blog = $this->get( 'doctrine' )->getManager()->getRepository( "BloggerBlogBundle:Blog" )->find( $id );
            
            if( !$blog )
            {
           		$blog = new Blog();
            }

            return $this->render( 'FiveAdminBundle:Action:edit_blog.html.twig', array(            
                'form'               => $this->get( $params[ 'form' ] )->createView(),
                'form_action'        => $params[ 'form_action' ],
                'blog'            => $blog,
                'id'                 => $id,
                'options' =>  $options
            ) );
    }

    public function updateAction( Request $Request /*,$options*/ )
    {
/*        if( $this->container->get('security.context')->isGranted( 'ROLE_ADMIN' )  )
        {  */      


            $this->checkAccessGranted( true );

            $data       = $Request->request->get( 'data' );
            
            $Blog    = false;

            if( $data[ 'id' ] != '' && !empty( $data[ 'id' ] ) && is_numeric($data[ 'id' ]) )
            {
                $Blog = $this->get( 'doctrine' )->getManager()->getRepository( "BloggerBlogBundle:Blog" )->find( $data[ 'id' ] );
            }     
            
            if( !$Blog )
            {
                $Blog     = new Blog();
            }
             
            if( $Blog )
            {


                $Blog->setTitleEn( $data[ 'titleTextEn' ] );
                $Blog->setTitleFr( $data[ 'titleTextFr' ] );
                
                $Blog->setIntroTextEn( $data[ 'introTextEn' ] );
                $Blog->setIntroTextFr( $data[ 'introTextFr' ] );
                
                if (  $data[ 'BlogEn' ] == null )
                {
                       $data[ 'BlogEn' ] = "";                  
                }
                
                if (  $data[ 'BlogFr' ] == null )
                {
                       $data[ 'BlogFr' ] = "";                  
                }
                $Blog->setBlogEn( $data[ 'BlogEn' ] );
                $Blog->setBlogFr( $data[ 'BlogFr' ] );
                
                
                $Blog->setImageText1En( $data[ 'imageText1En' ] );
                $Blog->setImageText1Fr( $data[ 'imageText1Fr' ] );
                
                $Blog->setImageText2En( $data[ 'imageText2En' ] );
                $Blog->setImageText2Fr( $data[ 'imageText2Fr' ] );

                $Blog->setImage1( $data[ 'image1' ] );
                $Blog->setImage2( $data[ 'image2' ] );
                $Blog->setImage( $data[ 'imageMain' ] );
                
                $Blog->setAuthor( $data[ 'author' ] );
                $Blog->setlikeCount( 0 );
               
                
                //============ check if permalink already exist ======================
                
                //==== not thing put here then use title sub 40
                if($data[ 'permaLinkEn' ] == ''  )
                {  
                    $data[ 'permaLinkEn' ] = strtolower( substr($data[ 'titleTextEn' ],0,40 ) ) ;               
                }
                
                if($data[ 'permaLinkFr' ] == ''  )
                {
                    $data[ 'permaLinkFr' ] =  strtolower( substr($data[ 'titleTextFr' ],0,40 ) ) ;               
                }
                
                $permalink_en = $this->cleanPermalink(str_replace( '_' ,'-', str_replace( ' ' , '-' , $data[ 'permaLinkEn' ] )  ) ) ;
                $permalink_fr = $this->cleanPermalink(str_replace( '_' ,'-', str_replace( ' ' , '-' , $data[ 'permaLinkFr' ] )  ) ) ;
 
                $permalink_en_count = $this->get( 'doctrine' )->getManager()->getRepository( "BloggerBlogBundle:Blog" )->countByPermalink( $permalink_en, 'en' , $Blog->getId() );
                
                if ( $permalink_en_count > 0 )
                    $permalink_en = $permalink_en."-".($permalink_en_count+1) ;
                else
                    $permalink_en = $permalink_en;
                
                $permalink_fr_count = $this->get( 'doctrine' )->getManager()->getRepository( "BloggerBlogBundle:Blog" )->countByPermalink( $permalink_fr, 'fr' , $Blog->getId() );
                
                if ( $permalink_fr_count > 0 )
                    $permalink_fr = $permalink_fr."-".($permalink_fr_count+1) ;
                else
                    $permalink_fr = $permalink_fr;

                $Blog->setPermaLinkEn( $permalink_en );
                $Blog->setPermaLinkFr( $permalink_fr );
                
                $Blog->setTags( $data[ 'tags' ] );
			
                $validator = $this->get('validator');
                $errors = $validator->validate($Blog);			

                    if (count($errors) > 0) {
                        /*
                         * Uses a __toString method on the $errors variable which is a
                         * ConstraintViolationList object. This gives us a nice string
                         * for debugging.
                         */
                        $errorsString = (string) $errors;
                         //$params  = $this->container->getParameter( $options );        
                        //return new Response($errorsString);
                        /*
                                  return $this->render('FiveAdminBundle:Action:edit_blog.html.twig', array(
                          'errors' => $errors,		
         'form'               => $this->get( $params[ 'form' ] )->createView(),
         'form_action'        => $params[ 'form_action' ],
         'blog'            => $Blog,
        // 'id'                 => $id,
         'options' =>  $options	       
         )); 
                        */
                                         $error_array = array();
                                         foreach ($errors as $error )
                                         {

                                                 $error_array[$error->getPropertyPath()] = $error->getMessage();
                                         }						 

                                         $data["error"] = $error_array;
                          return new JsonResponse( $data );  
                    }

                $this->get( 'doctrine' )->getManager()->persist( $Blog );    

                $this->get( 'doctrine' )->getManager()->flush();            
                
              
                $data[ 'id' ] = $Blog->getId();
                return new JsonResponse( $data );            
            }

            return new JsonResponse( $data );
        //}
    } 
    
    protected function cleanPermalink($string)
    {
        
         return preg_replace('/[^A-Za-z0-9\-]/', '', $string ); 
    }
    
    public function listAction( $options )
    {
        $this->checkAccessGranted( true );

        $params = $this->container->getParameter( $options );

        return $this->get( 'five.form_manager' )->listEntities( 
            $params
        );
    }    
    
/*


    public function updateAction( Request $Request )
    {
     
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
*/
    //private function 
/*
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

        $params[ 'options' ] = $params;
        
        $labels = $FilteredGalleries[0]->getAllCategoriesLabelList(true);
        $exclude = array( 'room', 'style' );

        return $this->get( 'five.form_manager' )->listEntities( 
            $params
        );
    }
    */


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
                $entity = $em->getRepository( 'BloggerBlogBundle:Blog' )->find( $id );

                if( $entity )
                {
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
        }
    }

 
/*
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
   */
}
