<?php

namespace Five\AdminBundle\Manager;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEntityManager extends BaseEntityManager implements UserProviderInterface
{
    public function loadUserByUsername( $username )
    {
        return $this->loadUserByEmail( $username );
    }

    public function loadUserByEmail( $email )
    {
        $User = $this->repo()->findOneBy( array( 'email' => $email, 'enabled' => true ) );

        if( $User )
        {
            return $User;
        }

        throw new UsernameNotFoundException(
            sprintf( 'User with email "%s" does not exist.', $email )
        );        
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername( $user->getUsername() );
    }

    public function supportsClass($class)
    {
        return $class === 'Five\AdminBundle\Entity\User';
    }
    

}