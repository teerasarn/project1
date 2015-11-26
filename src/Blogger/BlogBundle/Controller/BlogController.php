<?php
// src/Blogger/BlogBundle/Controller/BlogController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;


/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * Show a blog entry
     */
    public function showAction( Request $request, $permalink ,  $_locale   )
    {

	if ( $permalink == '' )
        {
            return $this->redirectToRoute('blog_'.$_locale );  
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $blog = $em->getRepository('BloggerBlogBundle:Blog')->findByPermalink($permalink, $_locale);

        if ( !$blog  )
        {
              return $this->redirectToRoute('blog_'.$_locale );
        }    	
        
        $comments = $em->getRepository('BloggerBlogBundle:Comment')
                   ->getCommentsForBlog($blog->getId());
        
        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
            'page' => 'blog_article',
            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
            'blog_id' =>   $blog->getId(),
            'blog'      => $blog,
            'comments'  => $comments,
        ));
        
//    	 //===== this check already add main controller =================
//        $em = $this->getDoctrine()->getEntityManager();
//        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($blog_id);
// 	//==== this check already add main controller =================
//        if (!$blog) {
//            throw $this->createNotFoundException('Unable to find Blog post.');
//        }
//        
//        $comments = $em->getRepository('BloggerBlogBundle:Comment')
//                   ->getCommentsForBlog($blog->getId());
//
//
//        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
//        'blog'      => $blog,
//        'comments'  => $comments,
//         'page' => 'blog_article',
//            'branches' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->fetchBranches($_locale),
//            'BranchesEntity' => $this->getDoctrine()->getRepository('FiveCuisinesVerdunBundle:Branch')->findAll(),
//            'is_mobile' => $this->container->get('session')->get("isMobile") ? "true" : "false",
//   	 ));
    }
    
    public function likeClickAction(Request $Request)
    {

        $data = $Request->request->get( 'data' );
        
        
        $em = $this->getDoctrine()->getEntityManager();
        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($data["id"]);
        
        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
               
        $like_count = $blog->getLikeCount() + 1;
        
        $blog->setLikeCount($like_count);
        
        $this->get( 'doctrine' )->getManager()->persist( $blog );    
        $this->get( 'doctrine' )->getManager()->flush();
               
        $em = $this->getDoctrine()->getEntityManager();
                
        return new JsonResponse( $blog->getLikeCount() );
        
    }
}