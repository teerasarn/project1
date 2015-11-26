<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// Import new namespaces
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    public function indexAction($display_row = null, $display_col = null , $blog_page = 1 ,$blog_tag = "" )
    {     
        $limit = 9; 	
        if ( isset($display_row) && isset($display_col) )
                $limit = $display_row*$display_col;
                        $start_blog = $limit * ($blog_page-1) ; 
    	
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                ->getLatestBlogs($limit,$start_blog ,$blog_tag );  

        
        $total = $em->getRepository('BloggerBlogBundle:Blog')->getBlogCount($blog_tag);
                
        $total_page = ceil($total/$limit);
        
        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs ,
            'display_row' => $display_row,
            'display_col' => $display_col,
            'blog_page' => $blog_page,
            /// render here not work!!!!!
           // 'pagination' => $this->renderView('BloggerBlogBundle:Page:about.html.twig' , array(
            	'blog_page' => $blog_page,
            	'total_page' => $total_page,
            	'total' => $total,
            	'per_page' => $limit,
                'blog_tag' => $blog_tag
          //  	)
            
          //  )
        ));
    }
    
    public function homeBlogAction($limit = null)
    {
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs($limit);

        return $this->render('BloggerBlogBundle:Page:home.html.twig', array(
            'blogs' => $blogs
        ));
    }
    
    public function latestAction($limit = 4)
    {
    	
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs($limit);

        return $this->render('BloggerBlogBundle:Page:latest.html.twig', array(
            'blogs' => $blogs
        ));
       
    }
    
    
/* 
	 public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }    
    test function
	public function contactAction()
	{
	    $enquiry = new Enquiry();
	    $form = $this->createForm(new EnquiryType(), $enquiry);
	
	    $request = $this->getRequest();
	    if ($request->getMethod() == 'POST') {
	        $form->bindRequest($request);
	
	     if ($form->isValid()) {
		        
        $message = \Swift_Message::newInstance()
            ->setSubject('Contact enquiry from symblog')
            ->setFrom('enquiries@symblog.co.uk')
            ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
            ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
        $this->get('mailer')->send($message);		        
		        
		
		        $this->get('session')->setFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');
		
		        // Redirect - This is important to prevent users re-posting
		        // the form if they refresh the page
		        return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
    			}
	    }
	
	    return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
	        'form' => $form->createView()
	    ));
	}
*/    
}