<?php

namespace Five\CVApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RealisationController extends ApiAbstractController
{

    public function galleryAction( Request $Request, $_locale )
    {
    	$categories = array();
    	
    	$cat = $Request->request->get( 'categories', $Request->query->get( 'categories', null ) );

    	foreach ($cat as $key => $value) {
	     	if( !is_null( $cat ) && !empty( $cat ) )
	    	{
                if( $value != -1 && !empty($value) )
                {
                    $categories[] = $value;    
                }	
	    	}
    	}

    	if( empty($categories) )
    	{
    		$categories = array();
    	}

/*        $Galleries = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->findBy( 
            array( 'context'    => 'realisation' ), 
            array( 'position'   => 'ASC' ) 
        );*/
        
        $Galleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findBy( array( 'context' => 'realisation', 'enabled' => true ), array( 'position' => 'ASC' ) );
        //$this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $categories, 'realisation', true, true );

        $offset    = $Request->query->get( 'offset', 0 );
        $limit     = $Request->query->get( 'limit', null );    	
        
        $galleries = array();
        $images    = array();

        $curGalIds = array();
        $curImgIds = array();
        /*
    	foreach ($Galleries as $G) 
        {
            //if( $G->isEnabled() )
            //{
                $G->setEntityLocale( $_locale );
                $catIds = array();
                $catInIds = 0;

                foreach ($G->getCategories() as $cat )
                {
                    $catIds[] = $cat->getId();
                }

                foreach( $categories as $catId )
                {
                    if( in_array( $catId, $catIds ) )
                    {
                        $catInIds++;
                    }
                }

                if( $catInIds == count( $categories ) )
                {
                    $Gimgs = $G->getImages();
                    $imgs  = $Gimgs;

                    

                    foreach ($imgs as $row ) 
                    {
                        $row->setEntityLocale( $_locale );
                        //if( file_get_contents( __DIR__.'/../../../../web'.$row->getSrc()) )
                        //{

                        if( !in_array( $row->getId(), $curImgIds ) )
                        {
                            $images[] = array(
                                'id'          => $row->getId(),
                                'src'         => $row->getSrc(),
                                'src_thumb'   => $row->getSrcThumb(),
                                'title'       => $row->getTitle(),
                                'description' => $row->getDescription(),
                                'position'    => $row->getPosition()
                            );                        

                            $curImgIds[] = $row->getId();
                        }
                        //}
                    
                    }

                    if( !in_array( $row->getId(), $curGalIds ) )
                    {
                        $galleries[] = array(
                            'id'          => $G->getId(),
                            'total'       => count( $images ),
                            'title'       => $G->getTitle(),
                            'description' => $G->getDescription(),
                            'cover'       => $G->getCover(),
                            'images'      => $images
                        );

                        $curGalIds[] = $G->getId();
                    }
                }                
            //}

    	}*/

        //$limit = ( is_null( $limit ) || !is_numeric( $limit ) || $limit <= 0 || $limit > ( count( $images ) - 1 )  ) ? ( count( $images ) - 1 ) : $limit;
    	//$galleryImages = array_slice($images, $offset, $limit );
/*        $galleryImages = $images;

    	$struct = array( 
    		'categories' => $categories,
            'total' => count($galleries),
    		'galleries'    => $galleries,
    		'locale' => $_locale
    	);*/

    	//return new JsonResponse( $struct ); 
        $Galleries2 = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findBy( array( 'context' => 'realisation', 'enabled' => true ), array( 'position' => 'ASC' ) );
        //$this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findByCategoryIds( $categories, 'realisation' );
        
        $Gals       = array();        
        $CurImages  = array();
        $CurGal     = array();
        $curGalId   = null;
        
        foreach( $Galleries2 as $Gal )
        {
            $GalCategories = $Gal->getCategoriesIds();
            $Gal = $Gal->toArray();
            //var_dump($Gal['enabled']);
            

            $hasCategories    = false;
            $hasCategoriesCnt = 0;
            foreach( $categories as $checkCat )
            {                
                if( in_array( $checkCat, $GalCategories ) )
                {
                    $hasCategoriesCnt++;
                }
            }

            if( $hasCategoriesCnt == count( $categories ) )
            {
                $hasCategories = true;
            }

            if( $hasCategories === true && $Gal[ 'enabled' ] == 1 )
            {
                if( $Gal['id'] != $curGalId )
                {
                    if( !empty( $CurGal) )
                    {
                        $CurGal['total'] = count( $CurGal['images'] );
                        $Gals[]          = $CurGal;
                    }

                    $curGalId = $Gal['id'];
                    $CurGal   =  array(
                        'id'          => $Gal['id'],
                        'total'       => 0,
                        'title'       => $Gal['title'.ucfirst( $_locale ) ],
                        'description' => $Gal['description'.ucfirst( $_locale ) ],
                        'cover'       => $Gal['cover'],

                        'images'      => array()
                    );                
                }

                foreach ($Gal[ 'images' ] as $img )
                {
                    $CurGal['images'][] =  array(
                        'id'          => $img['id'],
                        'src'         => $img['src'],
                        'src_thumb'   => $img['srcThumb'],
                        'title'       => $img['title'.ucfirst( $_locale ) ],
                        'description' => $img['description'.ucfirst( $_locale ) ],
                        'position'    => $img['position']
                    );                
                }
            }
            //var_dump($Gal);

        }

        if(!empty($CurGal) )
        {
            $CurGal['total'] = count($CurGal['images']);
            $Gals[]          = $CurGal;
        }        

        //findByCategoryIds
        //print_r($Gals);

        $struct = array( 
            'categories' => $categories,
            'total'      => count($Gals),
            'galleries'  => $Gals,
            'locale'     => $_locale
        );                
        
        return new JsonResponse( $struct ); 
    }

    public function gallery2Action( Request $Request, $_locale )
    {
        $categories = array();
        
        $cat = $Request->request->get( 'categories', $Request->query->get( 'categories', null ) );

        foreach ($cat as $key => $value) {
            if( !is_null( $cat ) && !empty( $cat ) )
            {
                //$categories[$key] = explode( ',', $value);
                $categories[$key] = $value;
            }
        }

        if( empty($categories) )
        {
            $categories = array();
        }

        $Categories = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryImage" )->findImagesByCategories( $categories );

        $offset = $Request->query->get( 'offset', 0 );
        $limit  = $Request->query->get( 'limit', null );        


        $limit = ( is_null( $limit ) || !is_numeric( $limit ) || $limit <= 0 || $limit > ( count( $Categories ) - 1 )  ) ? ( count( $Categories ) - 1 ) : $limit;

        $images = array();

        foreach ($Categories as $row) {
            $images[] = array(
                'src'         => $row[ 'src' ],
                'src_thumb'   => $row[ 'src_thumb' ],
                'title'       => $row[ 'title_' . $_locale ],
                'description' => $row[ 'description_' . $_locale ],
                'position'    => $row[ 'position' ]
            );
        }

        $galleryImages = $images;
        //array_slice($images, $offset, $limit );

        $struct = array( 
            'categories' => $categories,
            'gallery'    => array(
                'total' => count( $galleryImages ),
                'title' => '',
                'description' => '',
                'images' => $galleryImages
            ),
            'locale' => $_locale
        );

        return new JsonResponse( $struct );
    }    
}
