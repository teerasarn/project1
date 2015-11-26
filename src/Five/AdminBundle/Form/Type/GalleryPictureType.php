<?php
namespace Five\AdminBundle\Form\Type;


use Symfony\Component\Form\FormBuilder;
/*use Symfony\Component\Form\FormBuilderInterface;*/
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AurayCapital\SiteBundle\Form\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\ButtonBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class GalleryPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     


        $builder
            ->add( $builder->create( 'image', 'hidden', array(                
                    "label"           => false,
                    //'image_path'      => '/bundles/fiveadmin/img/no-image.png',
                    'attr'            => array(
                        'placeholder' => 'Image name',
                        'nobsclass'   => true,
                        'simple_col'  => 6,
                        'class'       => 'gallery_name'
                    )                        
                ) )
            )
            ->add( 
                    $builder->create( 'titleFr', 'text', array(                
                    "label"           => false,
                    'attr'            => array(
                        'placeholder' => 'Title Fr',
                        'simple_col'  => 6,
                        'nobsclass'   => true,
                        //'widget_col' => 6
                    )                    
                ) )
            )
            ->add( 
                    $builder->create( 'titleEn', 'text', array(                
                    "label"           => false,
                    'attr'            => array(
                        'placeholder' => 'Title En',
                        'simple_col'  => 6,
                        'nobsclass'   => true,
                        //'widget_col' => 6
                    )                    
                ) )
            )            
            ;
    }    

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //var_dump($form->get( 'image' )->getData());
/*        $view->vars = array_replace(
            $view->vars,
            array(
                'allow_add'          => $options['allow_add'],
                'allow_delete'       => $options['allow_delete'],
                'add_button_text'    => $options['add_button_text'],
                'delete_button_text' => $options['delete_button_text'],
                'sub_widget_col'     => $options['sub_widget_col'],
                'button_col'         => $options['button_col'],
                'prototype_name'     => $options['prototype_name']
            )
        );

        if (false === $view->vars['allow_delete']) {
            $view->vars['sub_widget_col'] += $view->vars['button_col'];
        }

        if ($form->getConfig()->hasAttribute('prototype')) {
            $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
        }*/
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'required'     => false,
            'by_reference' => false,
            'compound'     => true,
            'label'        => false,
            'attr' => array(
            'class' => 'row form-group gallery_image',
            'empty' => ''
            //'style' => 'horizontal'
            
            )            
        ) );
    }

/*    public function getParent()
    {
        return 'text';
    }*/

    public function getName()
    {
        return 'gallery_picture';
    }
}