<?php

namespace Five\SeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $metasOptions =  array(
            'name' => array( 'type' => 'hidden', array(
                    //'label' => false, 
                    //'attr'  => array( 'nobsclass' => true ) 
                ) 
            ),
            'content' => array( 'type' => 'textarea' )
        ); 

        $builder
            ->add( 'route', 'hidden' )
            ->add( 'url', 'hidden' )
            ->add( 'locale', 'hidden' )
            ->add( 'title' )
            ->add('metasName', 'collection', array(
                'type'            => new \Five\AdminBundle\Form\Type\EmbeddedListType( $metasOptions ),
                'allow_add'       => true,
                'allow_delete'    => false,
                //'sub_widget_col'  => 10,
                //'button_col'      => 2,
                'prototype_name'  => '__seo_name__',
                //'label'           => false,
                'attr' => array(
                    /*'nobsclass' => true*/
                ),
                'options' => array(
                 //   'label' => false
                )
            ) )
/*            ->add('metasProperty', 'collection', array(
                'type'            => new \Five\AdminBundle\Form\Type\EmbeddedListType( $metasOptions ),
                'allow_add'       => true,
                'allow_delete'    => true,
                //'sub_widget_col'  => 10,
                //'button_col'      => 2,
                'prototype_name'  => '__seo_property__',
                'label'           => 'Metas Property'
            ) ) */           
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Five\SeoBundle\Entity\Seo',
            'required'   => false,
            //'label'      => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'five_seo';
    }
}
