<?php
namespace Five\SeoBundle\Listener;

use Symfony\Component\HttpKernel\Fragment\FragmentRendererInterface;
use Symfony\Component\HttpKernel\Fragment\RoutableFragmentRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpFoundation\Response;

class FragmentRendererListener extends RoutableFragmentRenderer implements FragmentRendererInterface
{
    public function render($uri, Request $request, array $options = array())
    {
        var_dump( $uri );
        $request->request->set( 'Test', 'sp' );
        return new Response();
    }

    public function getName()
    {
        return 'five_seo';
    }
}