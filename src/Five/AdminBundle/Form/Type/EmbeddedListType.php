<?php
namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmbeddedListType extends AbstractType
{
    private $fields;

    public function __construct( array $fields = array() )
    {
        $this->fields = $fields;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $defaultBoostrapCollection = array(
            'type'               => 'text',
            'allow_add'          => true,
            'allow_delete'       => true,
            'sub_widget_col'     => 10,
            'button_col'         => 2,
            'prototype_name'     => 'inlinep',
            'add_button_text'    => 'Add',
            'required'           => false,
            'options'            => array(
                'required'           => false
            )
        );

        foreach( $this->fields as $field => $config )
        {
            if( !isset( $config[ 'type' ] ) )
            {
                $config[ 'type' ] = 'text';
            }

            if( !isset( $config[ 'options' ] ) )
            {
                $config[ 'options' ] = array();
            }            

            if( $config[ 'type' ] == 'bootstrap_collection' )
            {
                if( isset( $config[ 'options' ] ) )
                {
                    $config[ 'options' ] = array_merge( $defaultBoostrapCollection, $config[ 'options' ] );
                }
            }

            $builder
                ->add( 
                        $builder->create( $field, $config[ 'type' ], $config[ 'options' ] )
                );             
        }
/*        $builder
            ->add( 
                    $builder->create( 'step', 'text', array(                
                    "label"        => 'Step',
                    'attr' => array(
                        //'placeholder' => 'Value',
                        'style' => 'horizontal',
                        //'simple_col' => 6
                    )
                ) )
            ) 
            ->add( 
                $builder->create( 'substeps', 'bootstrap_collection', array(
                    'type'               => 'text',
                    'allow_add'          => true,
                    'allow_delete'       => true,
                    'sub_widget_col'     => 10,
                    'button_col'         => 2,
                    'prototype_name'     => 'inlinep',
                    'add_button_text'    => 'Add Substep',
                    'required'           => false,
                    'options'            => array(
                        'required'           => false
                    )
                ) )
            )*/
            ;
    }    

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'required'     => false,
            'by_reference' => false,
            'compound'     => true,
            //'label'        => false,
            'attr' => array(
            
            //'style' => 'horizontal'
            
            )
        ) );
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'embedded_list';
    }
}