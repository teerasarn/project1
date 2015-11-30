<?php

namespace Five\CuisinesVerdunBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Five\CuisinesVerdunBundle\Entity\User;
use Five\CuisinesVerdunBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Redirect user.
     *
     */
    public function rootAction(Request $request, $_locale = "fr")
    {
        header("Location: /$_locale");
        exit();
    }

    public function indexAction(Request $request, $_locale)
    {
        return $this->render('FiveCuisinesVerdunBundle:Default:index.html.twig', array(
            'page' => 'home',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll()
        ));
    }

    public function servicesAction(Request $request, $_locale)
    {
        return $this->render('FiveCuisinesVerdunBundle:Default:services.html.twig', array(
            'page' => 'services',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll()

        ));
    }

    public function cuisineAction(Request $request, $_locale)
    {
/*        $StyleCategories = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:GalleryCategory')->findBy( array( 'context' => 'kitchen', 'parent' => 'style' ), array( 'position' => 'ASC' ) );

        $stylesIds = array();

        foreach ($StyleCategories as $style )
        {
            $stylesIds[] = $style->getId();
        }


        $MaterialCategories = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:GalleryCategory')->findBy( array( 'context' => 'kitchen', 'parent' => 'material' ), array( 'position' => 'ASC' ) );
        $materialsIds       = array();

        foreach ($MaterialCategories as $material )
        {
            $materialsIds[] = $material->getId();
        }

        $StyleGalleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $stylesIds, 'kitchen', false, true );
        $MaterialGalleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $materialsIds, 'kitchen', false, true );*/



        $StyleGalleriesList = $this->getGallery( 'style', 'kitchen');
        $StyleGalleries = $StyleGalleriesList[ 'entities' ];
        $StyleCategories = $StyleGalleriesList[ 'categories' ];

        $MaterialGalleriesList = $this->getGallery( 'material', 'kitchen');
        $MaterialGalleries = $MaterialGalleriesList[ 'entities' ];
        $MaterialCategories = $MaterialGalleriesList[ 'categories' ];    

        return $this->render('FiveCuisinesVerdunBundle:Default:cuisine.html.twig', array(
            'page' => 'cuisine',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            //'styles' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getList('kitchen'),
            'materials' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getList('kitchen'),
            'stylesImages' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getImages('kitchen'),
            'materialsImages' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getImages('kitchen'),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
            'materialGalleries' => $MaterialGalleries,
            'styles' => $StyleCategories,
            'styleGalleries' => $StyleGalleries,
        ));
    }

    public function salledebainAction(Request $request, $_locale)
    {
/*        $StyleCategories = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:GalleryCategory')->findBy( array( 'context' => 'bathroom', 'parent' => 'style' ), array( 'position' => 'ASC' ) );
        $stylesIds = array();

        foreach ($StyleCategories as $style )
        {
            $stylesIds[] = $style->getId();
        }


        $MaterialCategories = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:GalleryCategory')->findBy( array( 'context' => 'bathroom', 'parent' => 'material' ), array( 'position' => 'ASC' ) );
        $materialsIds = array();

        foreach ($MaterialCategories as $material )
        {
            $materialsIds[] = $material->getId();
        }*/

        //$StyleGalleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $stylesIds, 'bathroom', false, true );
        //$MaterialGalleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $materialsIds, 'bathroom', false, true  );



        $StyleGalleriesList = $this->getGallery( 'style', 'bathroom');
        $StyleGalleries = $StyleGalleriesList[ 'entities' ];
        $StyleCategories = $StyleGalleriesList[ 'categories' ];

        $MaterialGalleriesList = $this->getGallery( 'material', 'bathroom');
        $MaterialGalleries = $MaterialGalleriesList[ 'entities' ];
        $MaterialCategories = $MaterialGalleriesList[ 'categories' ];        


        return $this->render('FiveCuisinesVerdunBundle:Default:cuisine.html.twig', array(
            'page' => 'salledebain',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            'styles' => $StyleCategories,
            'styleGalleries' => $StyleGalleries,
            //$this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getList('bathroom'),
            'materials' => $MaterialCategories,
            //$this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getList('bathroom'),
            'materialGalleries' => $MaterialGalleries,
            //$this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getList('bathroom'),

            'stylesImages' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Style')->getImages('bathroom'),
            'materialsImages' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Material')->getImages('bathroom'),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll()
        ));
    }

    public function realisationsAction(Request $request, $_locale)
    {
        $em     = $this->getDoctrine()->getManager();
        $Rooms  = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array( 'parent' => 'room', 'context' => 'realisation' ), array( 'position' => 'ASC' ) );
        $Styles = $this->getDoctrine()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array( 'parent' => 'style', 'context' => 'realisation' ), array( 'position' => 'ASC' ) );

        return $this->render('FiveCuisinesVerdunBundle:Default:realisations.html.twig', array(
            'page' => 'realisations',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            'dropdownRooms' => $Rooms,
            'dropdownStyles' => $Styles
        ));
    }

    public function entrepriseAction(Request $request, $_locale)
    {
        return $this->render('FiveCuisinesVerdunBundle:Default:entreprise.html.twig', array(
            'page' => 'entreprise',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false"
        ));
    }

    public function contactAction(Request $request, $_locale)
    {
        return $this->render('FiveCuisinesVerdunBundle:Default:contact.html.twig', array(
            'page' => 'contact',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false"
        ));
    }

    public function infoAction(Request $request)
    {
        return $this->render('FiveCuisinesVerdunBundle:Default:info.html.twig', array(
            'info' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:User')->fetchMessageData( )
        ));
    }

    public function getGallery( $parent = 'style', $context = 'kitchen', $category = null )
    {
        $queryParent = $parent;
        $queryCat    = $category;
        
        /*$entities = $this->get( 'doctrine' )->getManager()->getRepository( $params[ 'entity' ] )->findAll();*/
        $galleries  = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:Gallery" )->findBy( array( 'context' => $context ) );

        // All categories for this context
        $categories = $this->get( 'doctrine' )->getManager()->getRepository( "FiveCuisinesVerdunBundle:GalleryCategory" )->findBy( array( 'context' => $context, 'parent' => $parent ), array( 'parent' => 'ASC', 'position' => 'ASC' ) );

        foreach ($categories as $cat) {
            //var_dump($cat->getTitle());
        }

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
           // var_dump($filteredCat->getTitle());
        }
        
        $FilteredGalleries  = $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Gallery')->findAllWithCategories( $filteredCatIds, $context, false, true );


        $allCats = array();
        foreach ($FilteredGalleries as $e )
        {
            if( $e->getCategories()->first()->getParent() == $parent )
            {
                //var_dump($e->getCategories()->first()->getTitle());
                $allCats[] = $e->getCategories()->first();    
            }
            
            
            //var_dump($e->getCategories()->first()->getParent());
        }

        $params = array();
        $params[ 'entities' ]   = $FilteredGalleries;
        $params[ 'categories' ] = $allCats;


        $params[ 'context' ] = $context;
        $params[ 'filters' ] = true;
        /*$params[ 'filters' ] = true;*/

        return $params;
    }
    
    //============ Blog action =====================
    public function blogAction(Request $request, $_locale, $blog_page = 1 , $blog_tag = "" )
    {

        return $this->render('FiveCuisinesVerdunBundle:Default:blog.html.twig', array(
            'page' => 'blog',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            'blog_page' => $blog_page,
            'blog_tag' =>  $blog_tag,
                
        ));
    }
    
//    public function blogTagAction(Request $request, $_locale, $blog_tag = "" , $blog_page = 1 )
//    {
//    	  //if ( $request->query->get('blog_page') == null )
//    	  //	  $blog_page = 1;
//    	  //else 
//    	  //	  $blog_page = $request->query->get('blog_page');
//    	  	  
//        return $this->render('FiveCuisinesVerdunBundle:Default:blog.html.twig', array(
//            'page' => 'blog',
//            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
//            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
//            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
//            'blog_page' => $blog_page,
//            'blog_tag' => $blog_tag,
//        ));
//    }
    
//    public function blogAricleAction(Request $request, $_locale , $permalink = '' )
//    { 
//           
//	if ( $permalink == '' )
//        {
//            return $this->redirectToRoute('blog_'.$_locale );  
//        }
//        
//        $em = $this->getDoctrine()->getEntityManager();
//    	//$blog = $em->getRepository('BloggerBlogBundle:Blog')->find($blog_id);
//
//        $blog = $em->getRepository('BloggerBlogBundle:Blog')->findByPermalink($permalink, $_locale);
//
//		if ( !$blog  )
//		{
//                      return $this->redirectToRoute('blog_'.$_locale );
//		}    	
//                     
//        return $this->render('FiveCuisinesVerdunBundle:Default:blogArticle.html.twig', array(
//            'page' => 'blog_article',
//            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
//            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
//            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
//            'blog_id' =>   $blog->getId() 
//        ));
//    }
    
//    Remove blog check feature    
//    public function blogAricleHomeAction(Request $request, $_locale , $permalink  = '' )
//    {
//        
//        $thisMonthBlog = $this->getDoctrine()->getRepository('BloggerBlogBundle:Blog')->getMonthlyBlogCount();
//        $blog_count = 3;
//
//        if ( $thisMonthBlog >= $blog_count  )
//        {
//            return $this->redirectToRoute('blog_'.$_locale);
//        }
//        else 
//        {
//            return $this->redirectToRoute('blog_article_'.$_locale , array('$permalink' => $permalink ) , 301 );
//        }
//        
//        
//        /*
//        return $this->render('FiveCuisinesVerdunBundle:Default:blogArticle.html.twig', array(
//            'page' => 'blog_article',
//            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
//            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
//            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
//            'blog_id' =>   $blog_id 
//        ));
//        */
//    }

}
