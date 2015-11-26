<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Assert\NotBlank;
use Symfony\Component\Validator\Constraints\Assert\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Material
 * @ORM\Table(name="cv_material")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\MaterialRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Material
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
     * @ORM\Column(name="fr", type="string", length=255, nullable=true)
     */
    protected $fr;

    /**
     * @var string
     *
     * @ORM\Column(name="en", type="string", length=255, nullable=true)
     */
    protected $en;

    /**
     * @var string
     *
     * @ORM\Column(name="fr_description", type="string", length=255, nullable=true)
     */
    protected $frDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="en_description", type="string", length=255, nullable=true)
     */
    protected $enDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set en
     *
     * @param string $en
     * @return Material
     */
    public function setEn($en)
    {
        $this->en = $en;

        return $this;
    }

    /**
     * Get en
     *
     * @return string
     */
    public function getEn()
    {
        return $this->en;
    }

    /**
     * Set fr
     *
     * @param string $fr
     * @return Material
     */
    public function setFr($fr)
    {
        $this->fr = $fr;

        return $this;
    }

    /**
     * Get fr
     *
     * @return string
     */
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * Set enDescription
     *
     * @param string $enDescription
     * @return Material
     */
    public function setEnDescription($enDescription)
    {
        $this->enDescription = $enDescription;

        return $this;
    }

    /**
     * Get enDescription
     *
     * @return string
     */
    public function getEnDescription()
    {
        return $this->enDescription;
    }

    /**
     * Set frDescription
     *
     * @param string $frDescription
     * @return Material
     */
    public function setFrDescription($frDescription)
    {
        $this->frDescription = $frDescription;

        return $this;
    }

    /**
     * Get frDescription
     *
     * @return string
     */
    public function getFrDescription()
    {
        return $this->frDescription;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Material
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}
