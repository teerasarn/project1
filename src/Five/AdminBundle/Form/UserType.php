<?php

namespace Five\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', 'text', array(
                'property_path' => 'firstName'
            ))
            ->add('last_name', 'text', array(
                'property_path' => 'lastName'
            ))
            ->add('email', 'email', array(
                'property_path' => 'email'
            ))
            ->add('postal_code', 'text', array(
                'property_path' => 'postalCode'
            ))                                 
            ->add('password', 'password', array(
                'property_path' => 'plainPassword'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Five\AdminBundle\Entity\User',
            'required'           => false,
            'validation_groups'  => array( 'Default' ),
            //'cascade_validation' => true            
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'five_admin_user';
    }
}
