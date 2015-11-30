<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Assert\NotBlank;
use Symfony\Component\Validator\Constraints\Assert\Regex;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 * @ORM\Table(name="cv_user")
 * @ORM\Entity(repositoryClass="Five\CuisinesVerdunBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements EntityLocaleInjectorInterface
{
/*
    public function toArray( $sensitive = false )
    {
        return array(
            'id_user_contest' => $this->getId(),
            'firstName'       => $this->getFirstName(),
            'lastName'        => $this->getLastName(),
            'email'           => $this->getEmail(),
            'dateOfBirth'     => ( $this->getdateOfBirth() != null ? $this->getdateOfBirth()->format( 'Y-m-d' ) : null ),
            'newsletterOptin' => $this->getNewsletterOptin(),
            'garnierOptin'    => $this->getGarnierOptin()
        );
    }
*/
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\PrePersist
    */
    public function beforePersist()
    {
        $this
            ->setCreatedAt( new \DateTime() )
        ;
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
    }
}
