<?php

namespace Five\CuisinesVerdunBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Five\CoreBundle\Repository\AbstractRepository as BaseRepo;
use Doctrine\ORM\QueryBuilder;

/**
 * GalleryCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GalleryCategoryRepository extends BaseRepo
{
    protected $namespace   = 'FiveCuisinesVerdunBundle';
    protected $entity      = 'GalleryCategory';
    protected $entityClass = 'FiveCuisinesVerdunBundle:GalleryCategory';	


	public function getByNames( $names = array(), $context = null )
	{

		$cats = "";

		foreach( $names as $name )
		{
			$cats .= "'$name',";
		}

		$cats = rtrim( ',', $cats );

		$sql = "SELECT c
				FROM " . $this->entityClass . " c
				WHERE c.context='$context' AND c.name IN (:cats)
				GROUP BY c.parent 
				ORDER BY c.position, c.id";

        $em         = $this->getEntityManager();
//$q = "SELECT b FROM FiveCuisinesVerdunBundle:Material b WHERE b.type = '$type'";
        return $this->getEntityManager()->createQuery($sql)->setParameter( ':cats', $names )->getResult();
	}    
}