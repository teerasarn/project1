<?php
// src/Blogger/BlogBundle/Entity/Blog.php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * @ORM\Entity(repositoryClass="Blogger\BlogBundle\Entity\Repository\BlogRepository")
 * @ORM\Table(name="blog")
 * @ORM\HasLifecycleCallbacks()
 */
class Blog  implements EntityLocaleInjectorInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $author;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $image;

    /**
     * @ORM\Column(type="text")
     */
    protected $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

	 /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $title_fr;
    
     /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $title_en;

    /**
     * @ORM\Column(type="string")
     */
    protected $image_1;
    
     /**
     * @ORM\Column(type="string")
     */
    protected $image_2;

	  /**
     * @ORM\Column(type="string")
     */
    protected $image_text_1_fr;
    
     /**
     * @ORM\Column(type="string")
     */
    protected $image_text_1_en;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $image_text_2_fr;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $image_text_2_en;

     /**
     * @ORM\Column(type="string")
     */
    protected $intro_text_fr;

	  /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $intro_text_en;

    /**
     * @ORM\Column(type="text")
     */
    protected $blog_fr;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $blog_en;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $perma_link_en;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $perma_link_fr;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $like_count;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    		
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
    }

    
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
       $this->setUpdated(new \DateTime());
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Blog
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }



    /**
     * Set image
     *
     * @param string $image
     *
     * @return Blog
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Blog
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Blog
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Blog
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }


    /**
     * Remove comment
     *
     * @param \Blogger\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\Blogger\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }


	
    /**
     * Set titleFr
     *
     * @param string $titleFr
     *
     * @return Blog
     */
    public function setTitleFr($titleFr)
    {
        $this->title_fr = $titleFr;

        return $this;
    }

    /**
     * Get titleFr
     *
     * @return string
     */
    public function getTitleFr()
    {
        return $this->title_fr;
    }

    /**
     * Set titleEn
     *
     * @param string $titleEn
     *
     * @return Blog
     */
    public function setTitleEn($titleEn)
    {
        $this->title_en = $titleEn;

        return $this;
    }

    /**
     * Get titleEn
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->title_en;
    }

    /**
     * Set image1
     *
     * @param string $image1
     *
     * @return Blog
     */
    public function setImage1($image1)
    {
        $this->image_1 = $image1;

        return $this;
    }

    /**
     * Get image1
     *
     * @return string
     */
    public function getImage1()
    {
        return $this->image_1;
    }

    /**
     * Set image2
     *
     * @param string $image2
     *
     * @return Blog
     */
    public function setImage2($image2)
    {
        $this->image_2 = $image2;

        return $this;
    }

    /**
     * Get image2
     *
     * @return string
     */
    public function getImage2()
    {
        return $this->image_2;
    }
//=========================
   /**
     * Set imageText1Fr
     *
     * @param string $imageText1Fr
     *
     * @return Blog
     */
    public function setImageText1Fr($imageText1Fr)
    {
        $this->image_text_1_fr = $imageText1Fr;

        return $this;
    }

    /**
     * Get imageText2Fr
     *
     * @return string
     */
    public function getImageText1Fr()
    {
        return $this->image_text_1_fr;
    }
//=========================================
    /**
     * Set imageText1En
     *
     * @param string $imageText1En
     *
     * @return Blog
     */
    public function setImageText1En($imageText1En)
    {
        $this->image_text_1_en = $imageText1En;

        return $this;
    }

    /**
     * Get imageText1En
     *
     * @return string
     */
    public function getImageText1En()
    {
        return $this->image_text_1_en;
    }
//===============================================
    /**
     * Set imageText2Fr
     *
     * @param string $imageText2Fr
     *
     * @return Blog
     */
    public function setImageText2Fr($imageText2Fr)
    {
        $this->image_text_2_fr = $imageText2Fr;

        return $this;
    }

    /**
     * Get imageText2Fr
     *
     * @return string
     */
    public function getImageText2Fr()
    {
        return $this->image_text_2_fr;
    }

    /**
     * Set imageText2En
     *
     * @param string $imageText2En
     *
     * @return Blog
     */
    public function setImageText2En($imageText2En)
    {
        $this->image_text_2_en = $imageText2En;

        return $this;
    }

    /**
     * Get imageText2En
     *
     * @return string
     */
    public function getImageText2En()
    {
        return $this->image_text_2_en;
    }

    /**
     * Set introTextFr
     *
     * @param string $introTextFr
     *
     * @return Blog
     */
    public function setIntroTextFr($introTextFr)
    {
        $this->intro_text_fr = $introTextFr;

        return $this;
    }

    /**
     * Get introTextFr
     *
     * @return string
     */
    public function getIntroTextFr()
    {
        return $this->intro_text_fr;
    }

    /**
     * Set introTextEn
     *
     * @param string $introTextEn
     *
     * @return Blog
     */
    public function setIntroTextEn($introTextEn)
    {
        $this->intro_text_en = $introTextEn;

        return $this;
    }

    /**
     * Get introTextEn
     *
     * @return string
     */
    public function getIntroTextEn()
    {
        return $this->intro_text_en;
    }

    /**
     * Set blogFr
     *
     * @param string $blogFr
     *
     * @return Blog
     */
    public function setBlogFr($blogFr)
    {
        $this->blog_fr = $blogFr;

        return $this;
    }

    /**
     * Get blogFr
     *
     * @return string
     */
    public function getBlogFr($length = null)
    {
    	  if (false === is_null($length) && $length > 0)
	        return substr($this->blog_fr, 0, $length);
	     else
	        return $this->blog_fr;
    }

    /**
     * Set blogEn
     *
     * @param string $blogEn
     *
     * @return Blog
     */
    public function setBlogEn($blogEn)
    {
        $this->blog_en = $blogEn;

        return $this;
    }

    /**
     * Get blogEn
     *
     * @return string
     */
    public function getBlogEn($length = null)
    {
        if (false === is_null($length) && $length > 0)
	        return substr($this->blog_fr, 0, $length);
	     else
	        return $this->blog_en;
    }
    

     /**
     * Set perma_link_en
     *
     * @param string $perma_link_en
     *
     * @return PermaLinkEn
     */
    public function setPermaLinkEn($perma_link_en)
    {
        $this->perma_link_en = $perma_link_en;

        return $this;
    }
     /**
     * Get perma_link_en
     *
     * @return string
     */
    public function getPermaLinkEn()
    {
        return $this->perma_link_en;
    }

     /**
     * Set perma_link
     *
     * @param string $perma_link_fr
     *
     * @return PermaLinkFr
     */
    public function setPermaLinkFr($perma_link_fr)
    {
        $this->perma_link_fr = $perma_link_fr;

        return $this;
    }
     /**
     * Get perma_link
     *
     * @return string
     */
    public function getPermaLinkFr()
    {
        return $this->perma_link_fr;
    }
    
    /**
     * Set perma_link
     *
     * @param string $perma_link
     * @return PermaLink
   */ 
    public function setPermaLink($perma_link)
    {
        $method = $this->_getLocaleMethodIfExists( 'setPermaLink' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }
 
    
    /**
     * Get perma_link
     *
     * @return string 
      */  

    public function getPermaLink()
    {
        $method = $this->_getLocaleMethodIfExists( 'getPermaLink' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
    }        

     /**
     * Set likeCount
     *
     * @param integer $like_count
     */
    public function setlikeCount($like_count)
    {
        $this->like_count = $like_count;

        return $this;
    }
    
    /**
     * Get likeCount
     *
     * @return integer
     */
    public function getlikeCount()
    {
        return $this->like_count;
    }
   
    //============ multi language===============
 
    /**
     * Set title
     *
     * @param string $title
     * @return Title
   */ 
    public function setTitle($title)
    {
        $method = $this->_getLocaleMethodIfExists( 'setTitle' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }
 
    /**
     * Get title
     *
     * @return string 
      */  

    public function getTitle()
    {
        $method = $this->_getLocaleMethodIfExists( 'getTitle' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
    }    
 
    /**
     * Set blog
     *
     * @param string $blog
     *
     * @return Blog
     */
    public function setBlog($blog)
    {
        $method = $this->_getLocaleMethodIfExists( 'setBlog' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }

    /**
     * Get blog
     *
     * @return string
     */
    public function getBlog($length = null)
    {
        //return $this->blog;
        $method = $this->_getLocaleMethodIfExists( 'getBlog' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
        /*        
        if (false === is_null($length) && $length > 0)
	        return substr($this->blog, 0, $length);
	     else
	        return $this->blog;
        */
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Title
   */ 
    public function setIntroText($title)
    {
        $method = $this->_getLocaleMethodIfExists( 'setIntroText' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }
 
    /**
     * Get title
     *
     * @return string 
      */  

    public function getIntroText()
    {
        $method = $this->_getLocaleMethodIfExists( 'getIntroText' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
    }    
 
 
    public function setImageText1($title)
    {
        $method = $this->_getLocaleMethodIfExists( 'setImageText1' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }
 
    /**
     * Get title
     *
     * @return string 
      */  

    public function getImageText1()
    {
        $method = $this->_getLocaleMethodIfExists( 'getImageText1' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
    }    
    
        public function setImageText2($title)
    {
        $method = $this->_getLocaleMethodIfExists( 'setImageText2' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }
 
    /**
     * Get title
     *
     * @return string 
      */  

    public function getImageText2()
    {
        $method = $this->_getLocaleMethodIfExists( 'getImageText2' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
    }    

//============================Language==========================================

    private function _getLocaleMethodIfExists( $method )
    {
        $locale = ucfirst( $this->entity_locale );
        $m      = sprintf( "%s%s", $method, $locale );

        if( method_exists( $this, $m ) )
        {
            return $m;
        }

        return ( bool ) false;
    }    
    
    protected $entity_locale;   
     // EntityLocaleInjector interface
    public function getEntityLocale()
    {
        //return ( $this->entity_locale == null ? 'fr' : $this->entity_locale );
        if( !in_array( $this->entity_locale, array( 'en', 'fr' ) ) )
        {
            $this->entity_locale = 'fr';            
        }

        return $this->entity_locale;
    }

    public function setEntityLocale( $locale )
    {
        $this->entity_locale = $locale;

        if( !in_array( $this->entity_locale, array( 'en', 'fr' ) ) )
        {
            $this->entity_locale = 'fr';            
        }        

        return $this;
    }        

//================ pre persist ======



    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        $this->setUpdated( new \DateTime() );

    }
    


   public function getImageURL($image)
    {
    		$fs = new Filesystem();
    		$path =  __DIR__.'/../../../../web';

    		if( $fs->exists( $path.$image ) && $image != null && $image != '' )
    		{
    			return $image;
    		}
    		else 
    		{
    			return "/bundles/fiveadmin/img/no-image.png";	
    		}

    }
    
   public function isImageExist($image)
    {
    		$fs = new Filesystem();
    		$path =  __DIR__.'/../../../../web';
              
    		if( $fs->exists( $path.$image ) && $image != null && $image != '' )
    		{
    			return true;
    		}
    		else 
    		{
    			return false;	
    		}

    }    
    
    public function getAuthorAsURL()
    {
        $max_length = 10;
        return substr( str_replace( '_' , '-' ,  str_replace(' ', '-',  $this->author ) )  ,0,$max_length );  
    }
    
    public function getTitleAsURL()
    {
        $max_length = 40;
        return substr( str_replace( '_' , '-' ,  str_replace(' ', '-',  $this->getTitle() ) )  ,0,$max_length );  
    }
    
    public function getPermaLinkVal($locale )
    {
        $perma_link_method = "perma_link_".$locale;
        $title_method = "title_".$locale;
                
        if ( trim($this->$perma_link_method) == '' || $this->$perma_link_method == null  )
        {
             //echo "  qqqqq  ".replace( replace( substr($this->$title_method , 0 , 40  ) , ' ' , '-' ) ,'_' , '-' ) ;exit();   
            return str_replace( '_' , '-' ,str_replace( ' ' , '-' ,substr($this->$title_method , 0 , 40  ) ) ) ;
            //return   substr($this->$title_method , 0 , 40  )  ;
        }
        else 
        {
            return  $this->$perma_link_method; 
     
        }
       
         
    }

    public function getTagsList()
    {
        //[{"text":"Tag1"},{"text":"Tag2"},{"text":"tag4"}]
        
        $text =$this->tags ;
        preg_match('#\[(.*?)\]#', $text, $match);
        
        if ( $match == null )
        {
            return null;            
        }
        $text2 = $match[1];
        $match = preg_split( "/[{\s},]+/" , $text2);
        
        $result = null;
        
        foreach ($match as $item)
        {
            if( $item )
            {
                $data = array( 'tag'=>'','type'=>'' );
                preg_match('/"(?P<type>\V+)":"(?P<tag>\V+)"/', $item, $matches);
                
                if ( array_key_exists( "tag" , $matches ) )
                    $data["tag"] = $matches["tag"];
                
                if ( array_key_exists( "type" , $matches ) )
                    $data["type"] = $matches["type"];
                    
                $result[] = $data;
            }
            //$data[$matches["type"]] = $matches["tags"];
            //$result.push($data);
        }

        return $result;
  
    }   
    //======================= add locale here =======================
    function getDateLocale($date , $locale , $format)
    {
        if ( $locale == 'fr' )
         setlocale(LC_ALL, 'fr_FR.UTF-8');

        if( $format =='' )
        {
            if ( $locale == 'fr' )
            {
                 $format = '%e %B %Y';
            }
            else
            {
                $format = '%B %e, %Y';
            }
            
        }
        
        return strftime($format,  $date->getTimestamp() );
        
    }
}
