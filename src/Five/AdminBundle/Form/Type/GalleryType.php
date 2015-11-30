<?php

namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
#use AurayCapital\SiteBundle\Form\PictureType;

class GalleryType extends AbstractType
{
    private $CategoryRepo;

    public function __construct( EntityRepository $CategoryRepo )
    {
        $this->CategoryRepo = $CategoryRepo;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            //->add( 'mapPicture', new PictureType(), array( 'label' => false, 'attr' => array( 'nobsclass' => true ), 'image_path' => 'webPath' ) )
            //->add( 'discoverPicture', new PictureType(), array( 'label' => false, 'attr' => array( 'nobsclass' => true ),'image_path' => 'webPath' ) )

            ->add( 'gallery', 'bootstrap_collection', array(
                'type'            => 'gallery_picture',
                'allow_add'       => true,
                'allow_delete'    => true,
                'prototype'       => true,
                'by_reference'    => false,
                'add_button_text' => 'Add Image',
                'sub_widget_col'  => 10,
                'button_col'      => 2,                
                'label'           => 'Picture Gallery',
            ))
/*            ->add( 'translations', 'a2lix_translationsForms', array(
                'locales'          => array( 'en', 'fr' ),
                'required_locales' => array( 'en' ),
                'form_type'        => 'country_trans'
            ))         */

/*            ->add('seo', new \Five\SeoBundle\Form\SeoType(), array(
                'required'     => false,
                'by_reference' => false,
                'label'        => false
            ))          */  

/*            ->add( 'categories', 'entity', array(
                'class' => 'Five\CuisinesVerdunBundle\Entity\GalleryCategory',
                'multiple' => true,
                'expanded' => true,
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where( 'c.context = :context' )->setParameter(':context', 'realisation' )
                        ->orderBy('c.position', 'ASC')
                        ->orderBy('c.parent', 'ASC');
                }
            ) )*/
/*            ->add('enabled', 'choice', array(
                'choices' => array( 
                    true  => 'Published (visible)', 
                    false => 'Disabled (hidden)'
                ),
                'attr' => array(
                    
                )                
            ) )   */         
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Five\AdminBundle\Entity\Gallery',
            'required'   => false,
            'form_layout' => 'horizontal',
                'attr' => array(
                    
                )            
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'five_gallery';
    }
}
