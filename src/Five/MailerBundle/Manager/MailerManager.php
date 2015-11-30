<?php

namespace Five\MailerBundle\Manager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class MailerManager
{
    protected $templating;
    protected $mailer;
    protected $logger;
    protected $dispatcher;
    protected $options = array();
    protected $RequestStack;
    protected $environment;

    public function __construct( $dispatcher = null )
    {
        $this->dispatcher = $dispatcher;
    }

    public function getMailer()
    {
        return $this->mailer;
    }

    public function setMailer( $mailer )
    {
        $this->mailer = $mailer;

        return $this;
    }

    public function setTemplating( $templating )
    {
        $this->templating = $templating;

        return $this;
    }

    public function setLogger( $logger )
    {
        $this->logger = $logger;

        return $this;
    }    

    public function getTemplating()
    {
        return $this->templating;
    }

    public function setRequestStack( RequestStack $RequestStack )
    {
        $this->RequestStack = $RequestStack;
    }

    public function getRequest()
    {
        return $this->RequestStack->getCurrentRequest();
    }

    public function sendEmail( $persist = true )
    {
        if( $this->options[ 'template' ] != null )
        {
            $template_data                    = ( is_array( $this->options[ 'template_data' ] ) ? $this->options[ 'template_data' ] : array() );
            $this->options[ 'template_data' ] = $template_data;
            $this->options[ 'body' ]          = $this->render( $this->options[ 'template' ], $template_data );
        }

        $this->send( $this->options, $persist );

/*        $message = \Swift_Message::newInstance()
            ->setSubject( $this->options[ 'subject' ] )
            ->setBody( $this->options[ 'body' ] )
            ->setFrom( $this->options[ 'from' ] )
            ->setTo( $this->options[ 'to' ] )            
            ->setContentType( $this->options[ 'content-type' ] )
            ->setCharset( $this->options[ 'charset' ] )
        ;

        $result = $this->mailer->send( $message );*/

        return $this;
    }

    /**
     * @Five\MailerBundle\Aop\Annotation\SendEmail(name="send")
     */
    public function send( $options, $persist )
    {
        $message = \Swift_Message::newInstance()
            ->setSubject( $this->options[ 'subject' ] )
            ->setBody( $this->options[ 'body' ] )
            ->setFrom( $this->options[ 'from' ] )
            ->setTo( $this->options[ 'to' ] )            
            ->setContentType( $this->options[ 'content-type' ] )
            ->setCharset( $this->options[ 'charset' ] )
        ;
        
        $result = $this->mailer->send( $message );        

        return $result;        
    }

    public function setEnvironment( $env )
    {
        $this->environment = $env;
    }

    public function render( $view, array $parameters = array() )
    {
        return $this->templating->render($view, $parameters );
    }

    public function setDispatcher( EventDispatcherInterface $dispatcher )
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    public function getDispatcher( $dispatcher )
    {
        return $this->dispatcher;
    }    

    public function setOptions( array $options = array() )
    {
        $resolver = new OptionsResolver();
        
        $this->configureOptions( $resolver );        
        $this->options = $resolver->resolve( $options );

        return $this;
    }

    protected function configureOptions( OptionsResolverInterface $resolver )
    {                
        $resolver->setDefaults( array(
            'subject'       => null,
            'from'          => 'no-reply@auraycapital.com',
            'to'            => null,
            'body'          => null,
            'content-type'  => 'text/html',
            'charset'       => 'UTF8',
            'template'      => null,
            'template_data' => null,
            'ip_adress'     => null,
            'env'           => $this->environment,
            'context'       => 'five_mailer',
            'type'          => 'send_email',
        ) );

        $resolver->setRequired( array(
            'subject',
            'from',
            'to'
        ) );        
    }
}