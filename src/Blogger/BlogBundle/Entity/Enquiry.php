<?php
// src/Blogger/BlogBundle/Entity/Enquiry.php

namespace Blogger\BlogBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\Validator\Constraints\MinLength;
//use Symfony\Component\Validator\Constraints\MaxLength;

class Enquiry
{
    protected $name;

    protected $email;

	/**
     * @Assert\Length(
     *      min = 0,
     *      max = 20,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $subject;

	 /**
     * @Assert\Length(
     *      min = 5,
     *      max = 200,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $body;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());

        $metadata->addPropertyConstraint('email', new Email(array(
    'message' => 'symblog does not like invalid emails. Give me a real one!'
)));

         $metadata->addPropertyConstraint('subject', new NotBlank());
         $metadata->addPropertyConstraint('subject', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'Your first name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
        )));
        
        $metadata->addPropertyConstraint('body', new Assert\Length(array(
            'min'        => 5,
            'max'        => 200,
            'minMessage' => 'Your first name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
        )));
        //$metadata->addPropertyConstraint('subject', new MaxLength(50));

        //$metadata->addPropertyConstraint('body', new MinLength(50));
    }
    
    
}