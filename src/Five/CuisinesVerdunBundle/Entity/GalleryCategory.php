<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Branch
 * @ORM\Table(name="cv_gallery__category")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\GalleryCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class GalleryCategory implements EntityLocaleInjectorInterface
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
     * @ORM\Column(name="parent", type="string", length=255, nullable=true)
     */
    protected $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255, nullable=true)
     */
    protected $context;

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
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    protected $position;

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

    /**
     * @ORM\ManyToMany(targetEntity="Gallery", inversedBy="categories", cascade={"remove", "persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\JoinTable(name="cv_gallery__gallery_category",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=true)})
     */       
    protected $galleries;      

    protected $entity_locale;




    public function __construct()
    {
        $this->realisations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setEnabled( true );
    }

    public function toArray()
    {
        $exclude = array( 'galleries', 'categories' );
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

    /**
     * Get Galleries
     *
     * @return ArrayCollection 
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    public function addGallery( $gallery )
    {
        if( !$this->galleries->contains( $gallery ) )
        {
            $this->galleries[] = $gallery;
            $gallery->addCategory( $this );
        }
    }

    public function removeGallery($gallery)
    {
        $this->galleries->removeElement($gallery);
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

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }    

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @param string $parent
     * @return User
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

 
     /**
     * Set context
     *
     * @param string $context
     * @return User
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }    

    /**
     * @ORM\PrePersist
    */
    public function beforePersist()
    {
        if( $this->context == null )
        {
            $this->setContext( 'realisation' );
        }
        
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
            $this->$method( $title );
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
     * Set titleFr
     *
     * @param string $titleFr
     * @return GalleryCategory
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
     * @return GalleryCategory
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
     * @return GalleryCategory
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
     * @return GalleryCategory
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
     * Set position
     *
     * @param integer $position
     * @return GalleryCategory
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
     * @return GalleryCategory
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
     * @return GalleryCategory
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
}
