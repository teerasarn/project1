<?php

namespace Five\MailerBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mailer
 */
abstract class Mailer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255, nullable=true)
     */
    protected $context;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="from_email", type="string", length=255, nullable=true)
     */
    protected $fromEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="to_email", type="string", length=255, nullable=true)
     */
    protected $toEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=4294967295, nullable=true)
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    protected $template;

    /**
     * @var json_array
     *
     * @ORM\Column(name="template_data", type="json_array", nullable=true)
     */
    protected $templateData = array();

    /**
     * @var string
     *
     * @ORM\Column(name="env", type="string", length=10, nullable=true)
     */
    protected $env;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255, nullable=true)
     */
    protected $domain;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=16, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    protected $userAgent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    protected $classProperties = array();

    protected $reflectionClass = null;

    public function getClassProperties()
    {
        if( empty( $this->classProperties ) )
        {
            return $this->getReflectionClass()->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED );
        }

        return $this->classProperties;
    }

    public function hasClassMethod( $method )
    {
        return method_exists( $this, $method );
    }

    public function setClassPropertiesValue( array $propertiesValue = array() )
    {
        $classProperties = $this->getClassProperties();

        foreach( $classProperties as $property )
        {
            if( isset( $propertiesValue[ $property->getName() ] ) )
            {
                $expl    = explode( '_', $property->getName() );
                $keys    = ( is_array( $expl ) ? implode( array_map( 'ucfirst', $expl ) ) : $expl );
                $method  = sprintf( 'set%s', ucfirst( $keys ) );

                if( $this->hasClassMethod( $method ) )
                {
                    $this->$method( $propertiesValue[ $property->getName() ] );
                }
            }
        }

        return $this;
    }

    public function getReflectionClass()
    {
        if( $this->reflectionClass === null )
        {
            $this->reflectionClass = new \ReflectionClass( $this );
        }

        return $this->reflectionClass;
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
     * Set context
     *
     * @param string $context
     * @return Mailer
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Mailer
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Mailer
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fromEmail
     *
     * @param string $fromEmail
     * @return Mailer
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * Get fromEmail
     *
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set toEmail
     *
     * @param string $toEmail
     * @return Mailer
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    /**
     * Get toEmail
     *
     * @return string
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Mailer
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Mailer
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Mailer
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set templateData
     *
     * @param string $templateData
     * @return Mailer
     */
    public function setTemplateData($templateData)
    {
        $this->templateData = $templateData;

        return $this;
    }

    /**
     * Get templateData
     *
     * @return string
     */
    public function getTemplateData()
    {
        return $this->templateData;
    }

    /**
     * Set env
     *
     * @param string $env
     * @return Mailer
     */
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get env
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return Mailer
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Mailer
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return Mailer
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
     * Set userAgent
     *
     * @param string $userAgent
     * @return Mailer
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Mailer
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
     * @return Mailer
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
}
