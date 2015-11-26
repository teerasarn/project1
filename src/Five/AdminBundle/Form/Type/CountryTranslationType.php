<?php
namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder
            ->add( 'name' )
            ->add( 'description', 'textarea', array('label' => 'Country Description', 'attr' => array('class' => 'editor-basic')) ) // Ed. Enrique - ajout de la classe pour pouvoir mieux gérer les styles et l'attachement de l'éditeur
            ->add( 'familyText', 'textarea', array( 'label' => 'Family Life', 'attr' => array('class' => 'editor-basic')) )
            ->add( 'economicText', 'textarea', array('label' => 'Economic','attr' => array('class' => 'editor-basic')) )
            ->add( 'contactText', 'textarea', array( 'label' => 'Contact Text','attr' => array('class' => 'editor-basic') ) )
            ->add('keyFacts', 'bootstrap_collection', array(
                    'type'               => 'key_fact',
                    'allow_add'          => true,
                    'allow_delete'       => true,
                    'sub_widget_col'     => 10,
                    //'widget_col'         => 8,
                    'button_col'         => 2,
                    'prototype_name'     => 'inlinep',
                    'add_button_text'    => 'Add Key Fact',
                   // 'style' => 'inline',
                    'attr'            => array(
                        /*'style' => 'inline'*/
                    )
            ))            
            ;
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'data_class' => 'AurayCapital\SiteBundle\Entity\CountryTranslation',
            'required'   => false
        ) );
    }

    public function getName()
    {
        return 'country_trans';
    }
}