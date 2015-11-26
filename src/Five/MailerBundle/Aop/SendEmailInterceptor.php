<?php

namespace Five\MailerBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class SendEmailInterceptor implements MethodInterceptorInterface
{
    protected $logger;
    protected $annotationReader;
    protected $MailerEntityManager;

    public function __construct( \Doctrine\Common\Annotations\Reader $annotationReader, \Five\MailerBundle\Manager\MailerEntityManager $MailerEntityManager )
    {
        $this->annotationReader    = $annotationReader;
        $this->MailerEntityManager = $MailerEntityManager;
    }
/*    public function __construct( LoggerInterface $logger )
    {
        $this->logger = $logger;
        $this->logger->info('Construct');
    }*/

    public function intercept(MethodInvocation $invocation)
    {
        //$invocation->reflection->name
        //$this->logger->debug(sprintf('User "%s" invoked method "%s".', 'Oh yeah!' ));
        
        //return $invocation->proceed();
        
        //var_dump($invocation->arguments);
        $data   = isset( $invocation->arguments[ 0 ] ) ? $invocation->arguments[ 0 ] : array();
        $result = isset( $invocation->arguments[ 1 ] ) ? $invocation->arguments[ 1 ] : false;

        if( !empty( $data ) && is_array( $data ) && $result )
        {
            $this->MailerEntityManager->create( $data );    
        }
        
        $method        = $invocation->reflection;        
        $annotationObj = $this->annotationReader
                              ->getMethodAnnotation( 
            $method, 
            'Five\MailerBundle\Aop\Annotation\SendEmail'
        );

         return $invocation->proceed();
    }
}