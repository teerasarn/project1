<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Branch
 * @ORM\Table(name="cv_gallery__realisation")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\GalleryRealisationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class GalleryRealisation
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;    


    protected $image; 


    protected $category;


    protected $gallery;    

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255, nullable=true)
     */
    protected $context;

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


    public function __construct()
    {
        $this->setEnabled( true );
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images     = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set image
     *
     * @param \Five\CuisinesVerdunBundle\Entity\GalleryImage $image
     * @return GalleryRealisation
     */
    public function setImage(\Five\CuisinesVerdunBundle\Entity\GalleryImage $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Five\CuisinesVerdunBundle\Entity\GalleryImage 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param \Five\CuisinesVerdunBundle\Entity\GalleryCategory $category
     * @return GalleryRealisation
     */
    public function setCategory(\Five\CuisinesVerdunBundle\Entity\GalleryCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Five\CuisinesVerdunBundle\Entity\GalleryCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Gallery
     *
     * @param \Five\CuisinesVerdunBundle\Entity\Gallery $Gallery
     * @return GalleryRealisation
     */
    public function setGallery(\Five\CuisinesVerdunBundle\Entity\Gallery $Gallery)
    {
        $this->gallery = $Gallery;

        return $this;
    }

    /**
     * Get Gallery
     *
     * @return \Five\CuisinesVerdunBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }    
}
