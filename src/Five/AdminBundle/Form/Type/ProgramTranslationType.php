<?php
namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProgramTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $criteriasOptions =  array(
            'criteria'    => array( 'type' => 'text' ),
            'subcriteria' => array( 'type' => 'bootstrap_collection' )
        );

        $proceduresOptions =  array(
            'step'         => array( 'type' => 'text' ),
            'substeps'     => array( 'type' => 'bootstrap_collection' )
        ); 

        $costsOptions     =  array(
            'cost'        => array( 'type' => 'text' ),
            'description' => array( 'type' => 'text' ),
            'subcosts'    => array( 'type' => 'bootstrap_collection', 'options' => array( 'attr' => array( 'class' => 'col-md-12' ) ) )
        );                

        $builder
            ->add( 'title', 'text', array(
                'required' => true
            ) )
            ->add( 'subtitle' )
            ->add( 'description', 'textarea', array(
                'label'     => 'Program Description (intro. paragraph)', 
                'attr'      => array(
                    'class' => 'editor-basic')
            ) )
            ->add( 'benefitsText', 'textarea', array( 
                'label'     => 'Benefits Text', 
                'attr'      => array(
                    'class' => 'editor-basic')
            ) )
            ->add( 'costProcedure', 'text', array(
                'label' => 'Text before costs list'
            ) )
            ->add( 'criteriasText', 'textarea', array( 
                'label'     => 'Criterias Text (text above the criterias list)', 
                'attr'      => array(
                    'class' => 'editor-basic')
            ) )
            ->add( 'criteriasList', 'bootstrap_collection', array(
                'type'            => new \Five\AdminBundle\Form\Type\EmbeddedListType($criteriasOptions),
                'allow_add'       => true,
                'allow_delete'    => true,
                'sub_widget_col'  => 10,
                'button_col'      => 2,
                'prototype_name'  => 'inlinep',
                'add_button_text' => 'Add Criteria',
                'label'           => 'Criterias List'
            ))
            ->add( 'proceduresList', 'bootstrap_collection', array(
                'type'            => new \Five\AdminBundle\Form\Type\EmbeddedListType($proceduresOptions),
                'allow_add'       => true,
                'allow_delete'    => true,
                'sub_widget_col'  => 10,
                'button_col'      => 2,
                'prototype_name'  => 'inlinep',
                'add_button_text' => 'Add Procedure',
                'label'           => 'Procedures List'            
            ))
            ->add( 'costsList', 'bootstrap_collection', array(
                'type'            => new \Five\AdminBundle\Form\Type\EmbeddedListType( $costsOptions ),
                'allow_add'       => true,
                'allow_delete'    => true,
                'sub_widget_col'  => 10,
                'button_col'      => 2,
                'prototype_name'  => 'inlinep',
                'add_button_text' => 'Add Cost',
                'label'           => 'Costs List' ,
                'options' => array( 
                    'required' => false 
                )
            ))            
            ;
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'data_class' => 'AurayCapital\SiteBundle\Entity\ProgramTranslation',
            'required'   => false
        ) );
    }

    public function getName()
    {
        return 'program_trans';
    }
}