<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AbstractRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
abstract class AbstractRepository extends EntityRepository
{

    protected $namespace   = '';
    protected $entity      = '';

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
                ->getEntityManager()
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