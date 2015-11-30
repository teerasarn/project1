<?php

namespace Five\MailerBundle\Factory;

use Five\MailerBundle\Manager\MailerManager as BaseManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MailerFactory
{
    protected $options;
    protected $templating;
    protected $mailer;
    protected $dispatcher;

    public function __construct( $mailer = null, $templating, array $options = array() )
    {
        $this->options    = $options;
        $this->templating = $templating;
        $this->mailer     = $mailer;
        //$this->dispatcher = $dispatcher;
    }

    public function get()
    {
        $MailerManager = new BaseManager();
        $MailerManager
            ->setTemplating( $this->templating )
            ->setMailer( $this->mailer )
            //->setDispatcher( $this->dispatcher )
        ;

        return $MailerManager;
    }
}