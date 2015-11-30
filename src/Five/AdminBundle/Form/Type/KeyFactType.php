<?php
namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class KeyFactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder
            ->add( 
                    $builder->create( 'key', 'text', array(                
                    "label"        => false,
                    'attr' => array(
                        'placeholder' => 'Key',
                       // 'style' => 'horizontal',
                        'simple_col' => 6,
                        'nobsclass' => true
                    )                    
                ) )
            )
            ->add( 
                    $builder->create( 'value', 'text', array(                
                    "label"        => false,
                    'attr' => array(
                        'placeholder' => 'Value',
//                        'style' => 'horizontal',
                        'simple_col' => 6,
                        'nobsclass' => true
                    )
                ) )
            )            
            ;
    }    

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'required'     => false,
            'by_reference' => false,
            'compound'     => true,
            'label'        => false,
            'attr' => array(
            'class' => 'row form-group'
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
        return 'key_fact';
    }
}