<?php
namespace Five\CuisinesVerdunBundle\Entity;

// This was in folder /Interface/  but looks like PHP doesn't allowed reserved name in namespace...
interface EntityLocaleInjectorInterface
{
    function setEntityLocale( $locale );
    function getEntityLocale();
}