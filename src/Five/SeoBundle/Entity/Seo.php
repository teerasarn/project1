<?php

namespace Five\SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seo
 *
 * @ORM\Table(name="five_seo")
 * @ORM\Entity(repositoryClass="Five\SeoBundle\Entity\SeoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Seo
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
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\Column(name="metas_name", type="json_array", nullable=true)
     */
    private $metasName;

    /**
     * @var array
     *
     * @ORM\Column(name="metas_property", type="json_array", nullable=true)
     */
    private $metasProperty;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @ORM\PrePersist
    */
    public function beforePersist()
    {        
        $this
            ->setCreatedAt( new \DateTime() )
            ->setUpdatedAt( new \DateTime() )
        ;
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
     * Set route
     *
     * @param string $route
     * @return Seo
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Seo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return Seo
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Seo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function formatMetasNameList( $metasName )
    {
        $metas = array(
            array(
                'name'    => 'description', 
                'content' => null
            ),
            array(
                'name'    => 'keywords', 
                'content' => null
            )            
        );

        foreach( $metasName as $meta )
        {
            if( $meta[ 'name' ] == 'description' )
            {
                $metas[ 0 ][ 'content' ] = $meta[ 'content' ];
            }
            else
            if( $meta[ 'name' ] == 'keywords' )
            {
                $metas[ 1 ][ 'content' ] = $meta[ 'content' ];
            }            
            else
            {
                $metas[] = $meta;
            }
        }

        return $metas; 
    }

    /**
     * Set metasName
     *
     * @param array $metasName
     * @return Seo
     */
    public function setMetasName($metasName)
    {
        $this->metasName = $this->formatMetasNameList( $metasName );

        return $this;
    }

    /**
     * Get metasName
     *
     * @return array 
     */
    public function getMetasName()
    {
        return $this->formatMetasNameList( $this->metasName );
    }

    /**
     * Set metasProperty
     *
     * @param array $metasProperty
     * @return Seo
     */
    public function setMetasProperty($metasProperty)
    {
        $this->metasProperty = $metasProperty;

        return $this;
    }

    /**
     * Get metasProperty
     *
     * @return array 
     */
    public function getMetasProperty()
    {
        return $this->metasProperty;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Seo
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
     * @return Seo
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Seo
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

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
}
