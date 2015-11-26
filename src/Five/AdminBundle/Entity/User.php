<?php

namespace Five\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="five_admin_user")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Five\AdminBundle\Repository\UserRepository")
 */
class User implements UserInterface, EquatableInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=150, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=150, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=2, nullable=true)
     */
    private $province = 'QC';

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=6, nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=10, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="language_contact", type="string", length=2, nullable=true, options={"default":"en"})
     */
    private $languageContact = 'fr';

    /**
     * @var string
     *
     * @ORM\Column(name="language_site", type="string", length=2, nullable=true, options={"default":"en"})
     */
    private $languageSite = 'fr';

    /**
     * @var string
     *
     * @ORM\Column(name="device_type", type="string", length=10, nullable=true, options={"default":"desktop"})
     */
    private $deviceType;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=16, nullable=true, options={"default":"0"})
     */
    private $ipAddress;

    /**
     * @var string
     * Not persisted, simply holds password in clear text
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="password_salt", type="string", length=255, nullable=true)
     */
    private $passwordSalt;

    /**
     * @var string
     *
     * @ORM\Column(name="password_reset_token", type="string", length=255, nullable=true)
     */
    private $passwordResetToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="password_request_at", type="datetime", nullable=true)
     */
    private $passwordRequestAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="password_reset_at", type="datetime", nullable=true)
     */
    private $passwordResetAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="login_at", type="datetime", nullable=true)
     */
    private $loginAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", options={"default":false})
     */
    private $enabled;

    private $roles;

    public function __construct()
    {
        $this->passwordSalt = base_convert( sha1( uniqid( mt_rand(), true) ), 16, 36 );
        $this->enabled      = false;
        $this->roles        = array( 'ROLE_ADMIN' );
        $this->enabled      = false;
    }


    public function isEqualTo( UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->passwordSalt !== $user->getSalt()) {
            return false;
        }

        if ($this->email !== $user->getEmail()) {
            return false;
        }

        return true;
    }

    public function getRoles()
    {
        return array( 'ROLE_ADMIN' );
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->getPasswordSalt();
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @ORM\PrePersist
    */
    public function beforePersist()
    {        
        $this
            ->setCreatedAt( new \DateTime() )
            ->setUpdatedAt( new \DateTime() )
        ;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdate()
    {
        $this->setUpdatedAt( new \DateTime() );
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return BaseUserLogin
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return BaseUserLogin
     */
    public function setFirstName($firstName)
    {
        $this->firstName = trim( $firstName );

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return BaseUserLogin
     */
    public function setLastName($lastName)
    {
        $this->lastName = trim( $lastName );

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return BaseUserLogin
     */
    public function setEmail($email)
    {
        $this->email = self::formatLower( $email );

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return self::formatLower( $this->email );
    }   

    /**
     * Set province
     *
     * @param string $province
     * @return BaseUserLogin
     */
    public function setProvince( $province )
    {
        $this->province = self::formatUpper( $province );

        return $this;
    }

    /**
     * Get province
     *
     * @return string 
     */
    public function getProvince()
    {
        return self::formatUpper( $this->province );
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return BaseUserLogin
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = self::formatPostalCode( $postalCode );

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return self::formatUpper( $this->postalCode );
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return BaseUserLogin
     */
    public function setTelephone($telephone)
    {
        $this->telephone = preg_replace( '/[^0-9]/s','', $telephone );

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     * @return BaseUserLogin
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime 
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return BaseUserLogin
     */
    public function setGender($gender)
    {
        $this->gender = self::formatGender( $gender );

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        if( isset( $this->gender ) )
        {
            return self::formatGender( $this->gender );    
        }        
    }

    /**
     * Set languageContact
     *
     * @param string $languageContact
     * @return BaseUserLogin
     */
    public function setLanguageContact($languageContact)
    {
        $this->languageContact = self::formatLanguage( $languageContact );

        return $this;
    }

    /**
     * Get languageContact
     *
     * @return string 
     */
    public function getLanguageContact()
    {
        if( isset( $this->languageContact ) )
        {
            return self::formatLanguage( $this->languageContact );
        }        
    }

    /**
     * Set languageSite
     *
     * @param string $languageSite
     * @return BaseUserLogin
     */
    public function setLanguageSite($languageSite)
    {
        $this->languageSite = self::formatLanguage( $languageSite );

        return $this;
    }

    /**
     * Get languageSite
     *
     * @return string 
     */
    public function getLanguageSite()
    {
        return self::formatLanguage( $this->languageSite );
    }

    /**
     * Set deviceType
     *
     * @param string $deviceType
     * @return BaseUserLogin
     */
    public function setDeviceType($deviceType)
    {
        if( isset( $this->deviceType ) )
        {
            $this->deviceType = self::formatLower( $deviceType );
        }        

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return string 
     */
    public function getDeviceType()
    {
        if( isset( $this->deviceType ) )
        {        
            return self::formatLower( $this->deviceType );
        }
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return BaseUserLogin
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return BaseUserLogin
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set plainPassword
     *
     * @param string $plainPassword
     * @return BaseUserLogin
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    
        return $this;
    }

    /**
     * Get plainPassword
     *
     * @return string 
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }    

    /**
     * Set password
     *
     * @param string $password
     * @return BaseUserLogin
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set passwordSalt
     *
     * @param string $passwordSalt
     * @return BaseUserLogin
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
    
        return $this;
    }

    /**
     * Get passwordSalt
     *
     * @return string 
     */
    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }

    /**
     * Set passwordResetToken
     *
     * @param string $passwordResetToken
     * @return BaseUserLogin
     */
    public function setPasswordResetToken($passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    
        return $this;
    }

    /**
     * Get passwordResetToken
     *
     * @return string 
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * Set passwordRequestAt
     *
     * @param \DateTime $passwordRequestAt
     * @return BaseUserLogin
     */
    public function setPasswordRequestAt($passwordRequestAt)
    {
        $this->passwordRequestAt = $passwordRequestAt;

        return $this;
    }

    /**
     * Get passwordRequestAt
     *
     * @return \DateTime 
     */
    public function getPasswordRequestAt()
    {
        return $this->passwordRequestAt;
    }

    /**
     * Set passwordResetAt
     *
     * @param \DateTime $passwordResetAt
     * @return BaseUserLogin
     */
    public function setPasswordResetAt($passwordResetAt)
    {
        $this->passwordResetAt = $passwordResetAt;

        return $this;
    }

    /**
     * Get passwordResetAt
     *
     * @return \DateTime 
     */
    public function getPasswordResetAt()
    {
        return $this->passwordResetAt;
    }

    /**
     * Set loginAt
     *
     * @param \DateTime $loginAt
     * @return BaseUserLogin
     */
    public function setLoginAt($loginAt)
    {
        $this->loginAt = $loginAt;

        return $this;
    }

    /**
     * Get loginAt
     *
     * @return \DateTime 
     */
    public function getLoginAt()
    {
        return $this->loginAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return BaseUserLogin
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BaseUserLogin
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return BaseUserLogin
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /** 
     * get entity_locale
     * 
     * @return entity_locale
     */
    public function getEntityLocale()
    {
        return ( $this->entity_locale == null ? 'en' : $this->entity_locale );
    }

    /** 
     * set entity_locale
     * 
     * @param string $entity_locale
     * @return BaseUserLogin
     */
    public function setEntityLocale( $entity_locale )
    {
        $this->entity_locale = $entity_locale;

        return $this;
    }

    public static function getGenders()
    {
        return array( 'm', 'f' );
    }

    public static function getLanguages()
    {
        return array( 'en', 'fr' );
    }

    public static function formatLanguage( $language )
    {
        $language = trim( strtolower( $language ) );

        if( in_array( $language, self::getLanguages() ) )
        {
            return $language;
        }

        return 'en';
    }

    public static function formatGender( $gender )
    {
        $gender = trim( strtolower( $gender ) );

        if( in_array( $gender, self::getGenders() ) )
        {
            return $gender;
        }

        return null;
    }

    public static function formatPostalCode( $postalCode )
    {
        return str_replace( ' ', '', strtoupper( trim( $postalCode ) ) );
    }

    public static function formatDate( $date, $format = 'Y-m-d' )
    {
        if( !$date instanceof \DateTime )
        {
            $dt = \DateTime::createFromFormat( $format, $date );
            
            if( $dt !== false )
            {
                return $dt;
            }
        }

        return null;
    }

    public static function formatLower( $str )
    {
        return ( !is_null( $str ) ? trim( strtolower( $str ) ) : null );
    }

    public static function formatUpper( $str )
    {
        return ( !is_null( $str ) ? trim( strtoupper( $str ) ) : null );
    }  

    public function toArray()
    {
        return array(
            'id'               => $this->getId(),
            'email'            => $this->getEmail(),
            'roles'            => implode( ',', $this->getRoles() )
        );
    }


    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }      

    public function getUserType()
    {
        return 'administrator';
    }
}
