<?php

namespace Five\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AbstractRepository
 *
 */
abstract class AbstractRepository extends EntityRepository
{

    protected $namespace   = '';
    protected $entity      = '';

    protected $defaultLocale = 'en';
    protected $currentLocale = null;


    public function setDefaultLocale( $locale = 'en' )
    {
        $this->defaultLocale = $locale;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    public function setCurrentLocale( $locale )
    {
        $this->currentLocale = $locale;
    }

    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    protected function getLocale( $locale = null )
    {
        return ( null === $locale ? $this->defaultLocale : $locale );
    }    

    /***************************************************************************/
    # Define some useful shortcut methods
    /***************************************************************************/

    protected function _getQueryResults( $query, $data = array() )
    {
        return $this
                ->_createQuery( $query )
                ->setParameters( $data )
                ->getResult();
    }

    protected function _getQuerySingleResult( $query, $data = array() )
    {
        return $this
                ->_createQuery( $query )
                ->setParameters( $data )
                ->getSingleResult();
    }

    protected function _createQuery( $query )
    {
        return $this
                ->getManager()
                ->createQuery( $query );
    }

    protected function _saveEntity( $entity )
    {
        $em = $this->getEntityManager();
        $em->persist( $entity );
        $em->flush();
    }

    public function save( $entity )
    {
        return $this->_saveEntity( $entity );
    }

    public function em()
    {
        return $this->getEntityManager();
    }

    public function getRepo( $repo )        
    {
        return $this->getEntityManager()->getRepository( $repo );
    }

    public function repo( $repo )     
    {
        return $this->getRepo( $repo );
    }

    public function getEntityName( $alias = '' )
    {
        return $this->namespace . ":" . $this->entity . " " . $alias;
    }
}