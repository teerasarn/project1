<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Five\CuisinesVerdunBundle\Entity\GalleryRealisation;
/**
 * Branch
 * @ORM\Table(name="cv_gallery__image")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\GalleryImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class GalleryImage implements EntityLocaleInjectorInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title_fr", type="string", length=255, nullable=true)
     */
    protected $titleFr;

    /**
     * @var string
     *
     * @ORM\Column(name="description_fr", type="text", nullable=true)
     */
    protected $descriptionFr;

    /**
     * @var string
     *
     * @ORM\Column(name="title_en", type="string", length=255, nullable=true)
     */
    protected $titleEn;

    /**
     * @var string
     *
     * @ORM\Column(name="description_en", type="text", nullable=true)
     */
    protected $descriptionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=255, nullable=true)
     */
    protected $src;

    /**
     * @var string
     *
     * @ORM\Column(name="src_thumb", type="string", length=255, nullable=true)
     */
    protected $srcThumb;    

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="boolean", nullable=true)
     */
    protected $cover;

    /**
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="images")
     * 
     */
    protected $gallery;    

    protected $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    protected $entity_locale;    


    public function __construct()
    {
        $this->setEnabled( true );
        $this->setCover( false );
        $this->categories   = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->realisations = new \Doctrine\Common\Collections\ArrayCollection();        
    }

    public function toArray()
    {
        $exclude = array( 'gallery', 'categories' );
        $reflectionClass = new \ReflectionClass( get_class( $this ) );
        $props = $reflectionClass->getDefaultProperties();
        $data  = array();

        foreach( $props as $key => $value )
        {
            if( !in_array( $key, $exclude ) )
            {
                $data[ $key ] = $this->$key;
            }
        }

        return $data;
    }

    public function getGallery()
    {
        return $this->gallery;
    }

    public function setGallery( $gallery )
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function addCategory( $category )
    {
        $categories = $this->getCategories();

        if( !$categories->contains( $category ) )
        {
            $po = new GalleryRealisation();

            $po->setImage($this);
            $po->setCategory($category);

            $this->addRealisation($po);
        }
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    } 

    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCover()
    {
        return $this->cover;
    }        

    public function getAllCategoriesNameList( $ary = false )
    {
        $categories = $this->getCategories();
        $list = array();

        foreach ($categories as $category) {
            if( !in_array( $category->getName(), $list ) )
            {
                $list[] = $category->getName();    
            }
            if( !in_array( $category->getParent(), $list ) )
            {            
                $list[] = $category->getParent();
            }
        }

        if( !$ary )
        {
            return implode( ', ', $list);    
        }
        else
        {
            return $list;
        }
        
    }

    /**
     * @ORM\PrePersist
    */
    public function beforePersist()
    {
        $this->setCreatedAt( new \DateTime() );
        $this->setUpdatedAt( new \DateTime() );        
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        $this->setUpdatedAt( new \DateTime() );
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

    /**
     * Set title
     *
     * @param string $title
     * @return GalleryCategory
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
     * Set description
     *
     * @param string $description
     * @return GalleryCategory
     */
    public function setDescription($description)
    {
        $method = $this->_getLocaleMethodIfExists( 'setDescription' );
        
        if(  $method !== false )
        {
            $this->$method( $title );
        }

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        $method = $this->_getLocaleMethodIfExists( 'getDescription' );
        
        if(  $method !== false )
        {
            return $this->$method();
        }

        return '';
    }

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

    /**
     * Set src
     *
     * @param string $src
     * @return GalleryImage
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Remove category
     *
     * @param \Five\CuisinesVerdunBundle\Entity\GalleryCategory $categories
     */
    public function removeCategory(\Five\CuisinesVerdunBundle\Entity\GalleryCategory $category)
    {        
        foreach ($this->realisations as $r)
        {
            if( $r->getCategory()->getId() == $category->getId() )
            {
                $this->removeRealisation( $r );
            }

        }
        //$this->categories->removeElement($categories);
    }

    public function getCategories()
    {
        $categories = new ArrayCollection();
        
        foreach( $this->realisations as $r )
        {
            $categories[] = $r->getCategory();
        }

        return $categories;
    }

    public function setCategories( $categories )
    {
        foreach( $categories as $c )
        {
            $Realisation = new GalleryRealisation();

            $Realisation->setImage( $this );
            $Realisation->setCategory( $c );

            $this->addRealisation( $Realisation );
        }

    }    


   // Important 
    public function getCategory()
    {
        $categories = new ArrayCollection();
        
        foreach( $this->realisations as $p )
        {
            $categories[] = $p->getCategory();
        }

        return $categories;
    }
    // Important
    public function setCategory($categories)
    {
        foreach($categories as $p)
        {
            $po = new GalleryRealisation();

            $po->setImage($this);
            $po->setCategory($p);

            $this->addRealisation($po);
        }

    }

    /**
     * Set titleFr
     *
     * @param string $titleFr
     * @return GalleryImage
     */
    public function setTitleFr($titleFr)
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    /**
     * Get titleFr
     *
     * @return string 
     */
    public function getTitleFr()
    {
        return $this->titleFr;
    }

    /**
     * Set descriptionFr
     *
     * @param string $descriptionFr
     * @return GalleryImage
     */
    public function setDescriptionFr($descriptionFr)
    {
        $this->descriptionFr = $descriptionFr;

        return $this;
    }

    /**
     * Get descriptionFr
     *
     * @return string 
     */
    public function getDescriptionFr()
    {
        return $this->descriptionFr;
    }

    /**
     * Set titleEn
     *
     * @param string $titleEn
     * @return GalleryImage
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    /**
     * Get titleEn
     *
     * @return string 
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     * @return GalleryImage
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string 
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * Set srcThumb
     *
     * @param string $srcThumb
     * @return GalleryImage
     */
    public function setSrcThumb($srcThumb)
    {
        $this->srcThumb = $srcThumb;

        return $this;
    }

    /**
     * Get srcThumb
     *
     * @return string 
     */
    public function getSrcThumb()
    {
        return $this->srcThumb;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return GalleryImage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return GalleryImage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return GalleryImage
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add realisations
     *
     * @param \Five\CuisinesVerdunBundle\Entity\GalleryRealisation $realisations
     * @return GalleryImage
     */
    public function addRealisation(\Five\CuisinesVerdunBundle\Entity\GalleryRealisation $realisations)
    {
        $this->realisations[] = $realisations;

        return $this;
    }

    /**
     * Remove realisations
     *
     * @param \Five\CuisinesVerdunBundle\Entity\GalleryRealisation $realisations
     */
    public function removeRealisation(\Five\CuisinesVerdunBundle\Entity\GalleryRealisation $realisations)
    {
        $this->realisations->removeElement($realisations);
    }

    /**
     * Get realisations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRealisations()
    {
        return $this->realisations;
    }
}
