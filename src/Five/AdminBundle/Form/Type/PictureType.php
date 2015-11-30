<?php

namespace AurayCapital\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Five\AdminBundle\Form\DataTransformer\PictureUploadTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class PictureType extends AbstractType
{
/*    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
/*        $transformer = new PictureUploadTransformer($this->om);
        $builder->addModelTransformer($transformer);     */   
        $builder
            ->add( 'hash', 'hidden' )
            ->add( 'name', 'hidden')
            ->add( 'file', 'file', array( 
                'mapped'     => true,
                //'data_class' => null,
                'required'   => false,
                'label'      => false,
                'image_path' => $options[ 'image_path' ]
            ))
            ->add( 'extension', 'hidden' )
            ->add( 'width', 'hidden' )
            ->add( 'height', 'hidden' )
            ->add( 'position', 'hidden', array(
                'empty_data' => 0
            ))
            ->add('enabled', 'hidden', array(
                'empty_data' => 1
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Five\AdminBundle\Entity\Picture',
            'mapped'     => true,
            //'AurayCapital\SiteBundle\Entity\Picture',
            'required'   => false,
            'image_path' => null
        ));
    }

/*    public function getParent()
    {
        return 'file';
    }*/

    /**
     * @return string
     */
    public function getName()
    {
        return 'picture_upload';
    }
}
