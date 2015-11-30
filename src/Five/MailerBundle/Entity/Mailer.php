<?php

namespace Five\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Five\MailerBundle\Model\Mailer as BaseMailer;

/**
 * Mailer
 *
 * @ORM\Table(name="five_mailer")
 * @ORM\Entity(repositoryClass="Five\MailerBundle\Entity\MailerRepository")
 * @ORM\HasLifecycleCallbacks
 */

class Mailer extends BaseMailer
{

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
}
