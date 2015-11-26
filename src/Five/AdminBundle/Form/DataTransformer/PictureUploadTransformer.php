<?php
namespace Five\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Five\AdminBundle\Entity\Picture;

class PictureUploadTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($picture)
    {
        if (null === $picture) {
            return "";
        }


        return $picture;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($file)
    {
        if( empty($file) )
        {
            return null;
        }
/*        if (!$name) {
            return null;
        }*/

/*        $picture = $this->om
            ->getRepository('AurayCapitalSiteBundle:Picture')
            ->findOneBy(array('name' => $name))
        ;*/

/*        if (null === $picture) {
            throw new TransformationFailedException(sprintf(
                'An picture with number "%s" does not exist!',
                $picture
            ));
        }*/
        $picture = new Picture();
        $picture->setFile( $file );

        return $picture;
    }
}