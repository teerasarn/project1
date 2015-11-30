<?php

namespace Five\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Picture
 *
 * @ORM\Table(name="five_admin_picture",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="hash_idx",columns={"hash"})
 *      },
 *      indexes={ 
 *          @ORM\Index(name="hash_status_index", columns={"hash", "enabled"}),
 *      }
 * )
 * @ORM\Entity(repositoryClass="Five\AdminBundle\Entity\PictureRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Picture
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, unique=true)
     */
    private $hash;

    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=10, nullable=true)
     */
    private $extension;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", nullable=true)
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height; 

    /**
     * @ORM\Column(name="mime_type", type="string", nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(name="path", type="string", nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(name="size", type="decimal", nullable=true)
     */
    private $size;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;        

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="Gallery", mappedBy="pictures")
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $galleries;


    public function __construct()
    {
        $this->galleries = new ArrayCollection();        
        $this->setPosition( 0 );
        $this->setEnabled( false );
    }
    
    /**
     * Get galleries
     *
     * @return ArrayCollection 
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    public function addGallery( $gallery )
    {
        if( !$this->galleries->contains( $gallery ) )
        {
            $this->galleries[] = $gallery;
            $gallery->addPicture( $this );
        }        
    }

    public function removeGallery($gallery)
    {
        $this->galleries->removeElement($gallery);
    }
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getThumbAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getThumbUploadRootDir().'/' .$this->path;
    }    

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : '/'.$this->getUploadDir().'/'.$this->path;
    }

    public function getThumbWebPath()
    {
        return null === $this->path
            ? null
            : '/'.$this->getThumbUploadDir().'/'.$this->path;
    }      

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getThumbUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getThumbUploadDir();
    }    

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads';
    }

    protected function getThumbUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/thumbs';
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

public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            
            $this->extension = str_replace( 'jpeg', 'jpg', strtolower( $this->getFile()->guessExtension() ) );
            $this->path = $filename.'.'. $this->extension;
            $this->name = $filename.'.'.$this->extension;
            $this->hash = $filename;
            $this->size = $this->getFile()->getClientSize();

            $this->setMimeType( mb_strtolower( $this->getFile()->getMimeType() ) )
            //->setExtension( mb_strtolower( $info[ 'fileExtension' ] ) )
        ;

            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            //@unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        list( $width, $height, $type, $attr ) = @getimagesize( $this->getAbsolutePath() );

        if( isset( $width ) )
        {
            $this->setWidth( $width );
        }

        if( isset( $height ) )
        {
            $this->setHeight( $height );
        }        
        $this->file = null;
        $this->setEnabled(true);
    }

    public function generateThumb( $size = 100 )
    {   
        if( !is_dir( $this->getThumbUploadRootDir() ) )
        {
            if( !mkdir( $this->getThumbUploadRootDir(), 0755 ) )
            {
                return false;
            }
        }
        
        $ratio_orig = ( $this->getWidth() / $this->getHeight() );

        if( $this->getWidth() <= $this->getHeight() )
        {
            $height = $size;
            $width  = $height * $ratio_orig;
        }
        else
        {
            $width  = $size;
            $height = $width / $ratio_orig;
        }

        // Resample
        $image_p = imagecreatetruecolor($width, $height);
        $img     = $this->getAbsolutePath();

        switch( $this->getExtension() )
        {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($img);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight() );
                imagejpeg( $image_p, $this->getThumbAbsolutePath(), 85 );
            break;
            case 'gif':
                $image = imagecreatefromgif($img);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight() );
                imagegif( $image_p, $this->getThumbAbsolutePath(), 85 );    
            break;
            case 'png':
                $image = imagecreatefrompng($img);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight() );
                imagepng( $image_p, $this->getThumbAbsolutePath() );                
            break;
        }
        @imagedestroy( $image_p );
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
/*        if ($file = $this->getAbsolutePath()) {
            unlink($file);*/
        //}
    }


    public function setMetaData()
    {
        $file_name = basename( $info[ 'fileName' ] );
        $hash      = basename( $info[ 'fileWithoutExt' ] );

        $this
            ->setName( $file_name )
            ->setHash( $hash )
            ->setSize( $info[ 'fileSize' ] )
            ->setPath( $info[ 'filePath' ] )
            ->setMimeType( mb_strtolower( $info[ 'fileMimeType' ] ) )
            ->setExtension( mb_strtolower( $info[ 'fileExtension' ] ) )
        ;

        list( $width, $height, $type, $attr ) = @getimagesize( $info[ 'filePath' ] );

        if( isset( $width ) )
        {
            $this->setWidth( $width );
        }

        if( isset( $height ) )
        {
            $this->setHeight( $height );
        }
    }

    /**
     * Get galleries
     *
     * @return ArrayCollection 
     */
/*    public function getGalleries()
    {
        return $this->galleries;
    }

    public function addGallery( $gallery )
    {
        if( !$this->galleries->contains( $gallery ) )
        {
            $this->galleries[] = $gallery;
            $gallery->addPicture( $this );
        }        
    }

    public function removeGallery($gallery)
    {
        $this->galleries->removeElement($gallery);
    }*/

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
     * Set name
     *
     * @param string $name
     * @return Picture
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Picture
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    public function getFile()
    {
        return $this->file;
    }
  

    /**
     * Set extension
     *
     * @param string $extension
     * @return Picture
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    
        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Picture
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }    

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Picture
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param decimal $size
     * @return Picture
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return decimal 
     */
    public function getSize()
    {
        return $this->size;
    }    

    /**
     * Set width
     *
     * @param integer $width
     * @return Picture
     */
    public function setWidth($width)
    {
        $this->width = $width;
    
        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Picture
     */
    public function setHeight($height)
    {
        $this->height = $height;
    
        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    } 

    /**
     * Set position
     *
     * @param integer $position
     * @return Picture
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }            

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Picture
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Picture
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Picture
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

    public function __toString() 
    {
        return $this->name;
    }
}
