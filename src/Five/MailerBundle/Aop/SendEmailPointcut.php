<?php

namespace Five\MailerBundle\Aop;

#use JMS\AopBundle\Aop\PointcutInterface;

class SendEmailPointcut implements \JMS\AopBundle\Aop\PointcutInterface
{
    private $annotationReader;

    public function __construct(\Doctrine\Common\Annotations\Reader $annotationReader)
    {
        $this->annotationReader=$annotationReader;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return $class->getName() === 'Five\MailerBundle\Manager\MailerManager';
        //var_dump( $class->getName() );
        //return true;
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return $method->getName() === 'send';
        $annotationObj = $this->annotationReader
                              ->getMethodAnnotation( 
            $method,
            'Five\MailerBundle\Aop\Annotation\SendEmail'
        );
        //var_dump( $method->getName() == 'send' );
        //var_dump(  $method->getName() );
        //echo "<pre>";
        // echo "\n**".print_r($annotationObj,true)."\n";
        return isset( $annotationObj );
    }
}