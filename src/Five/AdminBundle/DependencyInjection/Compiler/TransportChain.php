<?php
namespace Five\AdminBundle\DependencyInjection\Compiler;

class TransportChain
{
    private $transports;

    public function __construct( $arg)
    {
        var_dump($arg);
        $this->transports = array();
    }

    public function addTransport( $transport, $attributes)
    {
        $this->transports[$attributes[ 'alias' ]] = array( 'transport' => $transport, 'attributes' => $attributes );
    }

    public function getTransport($alias)
    {
        if (array_key_exists($alias, $this->transports)) {
           return $this->transports[$alias];
        }
    }
}