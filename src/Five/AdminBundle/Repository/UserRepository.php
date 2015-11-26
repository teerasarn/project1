<?php

namespace Five\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 */
class UserRepository extends AbstractRepository
{
    protected $namespace   = 'FiveAdminBundle';
    protected $entity      = 'User';
    protected $entityClass = 'FiveAdminBundle:User';
}