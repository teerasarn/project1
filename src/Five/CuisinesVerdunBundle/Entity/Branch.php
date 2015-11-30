<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Assert\NotBlank;
use Symfony\Component\Validator\Constraints\Assert\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Branch
 * @ORM\Table(name="cv_branch")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\BranchRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Branch
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(name="street_en", type="string", length=255, nullable=true)
     */
    protected $street_en;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="city_en", type="string", length=255, nullable=true)
     */
    protected $city_en;

    /**
     * @var string
     *
     * @ORM\Column(name="postalcode", type="string", length=255, nullable=true)
     */
    protected $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true, unique=true)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="hours", type="string", length=255, nullable=true)
     */
    protected $hours;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_en", type="string", length=255, nullable=true)
     */
    protected $hours_en;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="division", type="integer", length=255, nullable=true)
     */
    protected $division;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", length=255, nullable=true)
     */
    protected $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", length=255, nullable=true)
     */
    protected $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=255, nullable=true)
     */
    protected $contactEmail;

    public function __construct()
    {
        $this->setEnabled( true );
    }


    public function setContactEmail($email)
    {
        $this->contactEmail = $email;

        return $this;
    }

    public function getContactEmail()
    {
        return $this->contactEmail;
    }    

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Branch
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

    public function getId()
    {
        return $this->id;
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
     * Set street
     *
     * @param string $street
     * @return User
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street_en
     *
     * @param string $street_en
     * @return User
     */
    public function setStreetEN($street_en)
    {
        $this->street_en = $street_en;

        return $this;
    }

    /**
     * Get street_en
     *
     * @return string
     */
    public function getStreetEN()
    {
        return $this->street_en;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city_en
     *
     * @param string $city_en
     * @return User
     */
    public function setCityEN($city_en)
    {
        $this->city_en = $city_en;

        return $this;
    }

    /**
     * Get city_en
     *
     * @return string
     */
    public function getCityEN()
    {
        return $this->city_en;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return User
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return User
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return User
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set hours
     *
     * @param string $hours
     * @return User
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return string
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set hours_en
     *
     * @param string $hours_en
     * @return User
     */
    public function setHoursEN($hours_en)
    {
        $this->hours_en = $hours_en;

        return $this;
    }

    /**
     * Get hours_en
     *
     * @return string
     */
    public function getHoursEN()
    {
        return $this->hours_en;
    }

    /**
     * Set division
     *
     * @param string $division
     * @return User
     */
    public function setDivision($division)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return string
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return User
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param string $division
     * @return User
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }


    /**
     * @ORM\PrePersist
    */
    public function beforePersist()
    {
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
    }
}
