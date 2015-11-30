<?php

namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WebTestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', 'choice', array(
                'choices' => array( 
                    'phantomjs' => 'Phantom Js',
                    'chrome'    => 'Chrome'
                ) )
            )        
            ->add('name')
            ->add('url')
            ->add('route')
            ->add('routeParams')
            ->add('targetEntity')
            ->add( 'testData', 'hidden', array(
                //'empty_data'   => json_encode( array( array( 'field'=>'first_name', 'value' => 'Simon' ), array( 'field'=>'first_name2', 'value' => 'Simon2' ) ) ),
                'data'         => json_encode( array( array( 'field'=>'first_name', 'value' => 'Simon' ), array( 'field'=>'first_name2', 'value' => 'Simon2' ) ) ),
            ) )            
/*            ->add( 'testData', 'collection', array(
                //'type'         => ,
                'label'        => null,               
                'allow_delete' => true,
                'allow_add'    => true,
                'prototype'    => true,
                'by_reference' => false,
                'data'   => json_encode( array( array( 'field'=>'first_name', 'value' => 'Simon' ), array( 'field'=>'first_name2', 'value' => 'Simon2' ) ) ),
                'options'  => array(
                    'required'  => false,
                )
            ) )*/
            //->add('databaseData')
            //->add('receivedData')
            //->add('screenshot')

            //->add('updatedAt')
            //->add('createdAt')
            //->add('enabled')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Five\AdminBundle\Entity\WebTest',
            'required'   => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
