<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Gallery
 *
 * @ORM\Table(name="cv_gallery")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\GalleryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Gallery implements EntityLocaleInjectorInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255, nullable=true)
     */
    protected $context;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255, nullable=true)
     */
    protected $cover = null;

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
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled_at", type="datetime", nullable=true)
     */
    private $enabledAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled_at", type="datetime", nullable=true)
     */
    private $disabledAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\OneToMany(targetEntity="GalleryImage", mappedBy="gallery", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position"="ASC"})
     */
    protected $images;

    /**
     * @ORM\ManyToMany(targetEntity="GalleryCategory", mappedBy="galleries", cascade={"persist","remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\JoinTable(name="cv_gallery__gallery_category",
     *      joinColumns={@ORM\JoinColumn(name="gallery_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")})
     */
    protected $categories;

    /**
     * @ORM\Column(name="tags", type="json_array", nullable=true)
     */
    protected $tags = array();

    protected $entity_locale;

    public function __construct()
    {
        $this->images     = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->setEnabled( false );
    }

    public function toArray()
    {
        $exclude = array( 'images', 'categories' );
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

        $data[ 'images' ]     = $this->collectionToArray( $this->getImages() );
        $data[ 'categories' ] = $this->collectionToArray( $this->getCategories() );

        return $data;
    }

    public function collectionToArray( $collection )
    {
        $data = array();

        foreach( $collection as $item )
        {
            $data[] = $item->toArray();
        }

        return $data;
    }

    public function addTag( $tag )
    {
        if( !in_array( $tag, $this->tags ) )
        {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags( $tags )
    {
        $this->tags = $tags;

        return $this;
    }

    public function getCover()
    {
        if( empty( $this->cover ) || $this->cover == null )
        {
            $cover = '/bundles/fiveadmin/img/no-image.png';
            $cover = null;

            foreach ($this->getImages() as $img)
            {
                if( $img->getCover() )
                {
                    $cover = $img->getSrc();
                }
            }

            if( $cover == null and $this->getImages()->count() > 0 && isset( $this->images[0] ) )
            {
                $cover = $this->images[ 0 ]->getSrc();
            }

            if( $cover == null )
            {

            }

            $this->cover = $cover;
            //return $cover;
        }

        return $this->cover;
    }

    public function getCoverImage()
    {
        $coverSrc = $this->getCover();

        foreach ($this->getImages() as $img)
        {
            if($img->getSrc() == $coverSrc )
            {
                return $img;
            }
        }

        return null;
    }

    public function getImagesCount()
    {
        return $this->getImages()->count();
    }

    public function setCover( $cover )
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCategoriesIds()
    {
        $categories = $this->getCategories();
        $list       = array();

        foreach( $categories as $category )
        {
            if( !is_null( $category->getId() ) )
            {
                $list[] = $category->getId();
            }            
        }

        return $list;
    }

/*    public function hasAllCategories( $hasCategories )
    {
        foreach($variable as $key => $value) {
            
        }
    }*/

    public function getAllCategoriesNameList( $ary = false )
    {
        $categories = $this->getCategories();
        $list       = array();

        foreach( $categories as $category )
        {
            if( !in_array( $category->getName(), $list ) && $category->getName() != null )
            {
                $list[] = $category->getName();
            }
            if( !in_array( $category->getParent(), $list ) && $category->getParent() != null )
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

    public function getAllCategoriesLabelList( $ary = false )
    {
        $list  = $this->getAllCategoriesNameList( true );
        $final = $list;

        if( $this->getContext() == 'realisation' )
        {
            $final = array_diff( $list, array( 'room', 'style' ) );
        }

        if( !$ary )
        {
            return implode( ', ', $final );
        }

        return $final;
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
        $this->setCover( $this->getCover() );
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        $this->setUpdatedAt( new \DateTime() );
        $this->cover = $this->getCover();
    }

    /**
     * Get images
     *
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    public function addImage( $Image )
    {
        if( !$this->images->contains( $Image ) )
        {
            if( $Image->getCover() )
            {
                $this->cover = $Image->getSrc();
            }
            $this->images[] = $Image;
            $Image->setGallery( $this );
        }
    }

    public function removeImage($Image)
    {
        if( $Image->getSrc() == $this->cover )
        {
            $this->cover = null;
        }

        $Image->setGallery(null);
        $this->images->removeElement($Image);
    }

    /**
     * Get categories
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories( $categories )
    {
        $this->categories = new ArrayCollection();

        foreach( $categories as $category )
        {
            $category->addGallery( $this );
            $this->categories[] = $category;
        }

        return $this;
    }

    public function addCategory( $category )
    {
        if( !$this->categories->contains( $category ) )
        {
            $category->addGallery( $this );
            $this->categories[] = $category;
        }
    }

    public function removeCategory($category)
    {
        $category->removeGallery( $this );
        $this->categories->removeElement($category);
    }

    public function removeCategories( $categories )
    {
        foreach( $categories as $cat )
        {
            $this->removeCategory( $cat );
        }

        //$category->removeGallery( $this );
        //$this->categories->removeElement($category);
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
     * Set name
     *
     * @param string $name
     * @return Gallery
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
     * Set position
     *
     * @param integer $position
     * @return Gallery
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Gallery
     */
    public function setEnabled($enabled)
    {
        //if( $this->enabled != ( bool ) $enabled )
        //{
            $this->enabled = ( bool ) $enabled;

            if( $this->enabled == true )
            {
                $this->setEnabledAt( new \DateTime() );
                $this->setDisabledAt( null );
            }
            else
            {
                $this->setDisabledAt( new \DateTime() );
                $this->setEnabledAt( null );
            }
        //}

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Gallery
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
     * Set EnabledAt
     *
     * @param \DateTime $EnabledAt
     * @return Gallery
     */
    public function setEnabledAt($EnabledAt)
    {
        $this->enabledAt = $EnabledAt;

        return $this;
    }

    /**
     * Get EnabledAt
     *
     * @return \DateTime
     */
    public function getEnabledAt()
    {
        return $this->enabledAt;
    }

    /**
     * Set disabledAt
     *
     * @param \DateTime $disabledAt
     * @return Gallery
     */
    public function setDisabledAt($disabledAt)
    {
        $this->disabledAt = $disabledAt;

        return $this;
    }

    /**
     * Get disabledAt
     *
     * @return \DateTime
     */
    public function getDisabledAt()
    {
        return $this->disabledAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Gallery
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
            $this->$method( $description );
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
            return stripslashes($this->$method());
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
}
