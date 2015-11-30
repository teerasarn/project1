<?php

namespace Five\MailerBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;

class MailerManagerListener
{
    public function handler( GenericEvent $event, $eventName, EventDispatcherInterface $dispatcher )
    {
        $dispatcher->dispatch( 'info', $event );
        $Mailer = $event->getSubject();
        $Options = $event->getArgument( 'options' );
        $Options[ 'body' ] = 'Coming for listener! Yeah';
        $Mailer->setOptions( $Options );
        $Mailer->sendEmail();
    }    
}