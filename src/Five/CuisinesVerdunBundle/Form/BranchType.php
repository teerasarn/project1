<?php

namespace Five\CuisinesVerdunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BranchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('contactEmail')
            ->add('street', 'text', array( 'label' => 'Address [FR]' ) )
            ->add('street_en', 'text', array( 'label' => 'Address [EN]' ) )
            ->add('city', 'text', array( 'label' => 'City [FR]' ) )
            ->add('city_en', 'text', array( 'label' => 'City [EN]' ) )
            ->add('postalCode')
            ->add('phone')
            ->add('fax')
            ->add('hours', 'textarea', array( 'label' => 'Business Hours [FR]','attr' => array('class' => 'editor-basic') ) )
            ->add('hours_en', 'textarea', array( 'label' => 'Business Hours [EN]','attr' => array('class' => 'editor-basic') ) )            
            ->add('division')
            ->add('lat')
            ->add('lng')
            
            ->add('enabled')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'Five\CuisinesVerdunBundle\Entity\Branch',
            'required'   => false
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cv_branch';
    }
}
