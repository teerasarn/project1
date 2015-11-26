<?php
namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArrayCollectionType extends AbstractType
{
    private $genderChoices;

    public function __construct( array $genderChoices = array() )
    {
        $this->genderChoices = $genderChoices;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $collection = array(
            'type'            => 'text',
            'allow_add'       => true,
            'allow_delete'    => true,
            'prototype'       => true,
            'sub_widget_col'  => 10,
            'button_col'      => 2,
            'prototype_name'  => 'inlinep',
            'add_button_text' => 'Add'  
        );

        foreach( $this->genderChoices as $key => $value )
         {
            $config = array(
                "label"        => false,
                'attr' => array(
                    'placeholder' => ucfirst( $key ),
                    'simple_col'  => 6
                )
            );            
            $configs = ( $value == '1bootstrap_collection' ) ? array_merge( $config, $collection ) : $config;
            //var_dump($configs);
            $builder->add( $builder->create( $key, $value, $configs ) );

/*            $builder->add( 
                $builder->create( $key, $value, array(
                
                    "label"        => false,
                    'attr' => array(
                        'placeholder' => ucfirst( $key ),
                        'simple_col'  => 6
                    )
                ) )
            );*/
         }
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'required'     => false,
            'by_reference' => false,
            //'compound'     => true,
                    'attr' => array(

                        'style' => 'horizontal'
                        
                    )
        ) );
    }

    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'array_collection';
    }
}