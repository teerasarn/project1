<?php

namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class BlogType extends AbstractType
{
    private $CategoryRepo;

    public function __construct( EntityRepository $CategoryRepo )
    {
        $this->CategoryRepo = $CategoryRepo;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add( 'author', 'text', array(
                'required' => true,
                'attr' => array(
                    
                )                
            ))
            /*
            ->add( 'titleEn', 'text', array(
                'required' => true,
                'attr' => array(
                    
                )                
            ))            
            ->add( 'descriptionFr' , 'textarea', array(
                'required' => true,
                'attr' => array(
                    "css" => 'editor-basic'
                )                
            ))
            ->add( 'descriptionEn' , 'textarea', array(
                'required' => true,
                'attr' => array(
                    "css" => 'editor-basic'
                )                
            ))            
            ->add( 'src', 'text', array(
                'required' => true,
                'attr' => array(
                    
                )                
            ))
            ->add( 'srcThumb', 'text', array(
                'required' => true,
                'attr' => array(
                    
                )                               
            ))            
            ->add( 'position' , 'number', array(
                'required' => true,
                'attr' => array(
                    
                )
            ))
            ->add('enabled', 'choice', array(
                'choices' => array( 
                    true  => 'Published (visible)', 
                    false => 'Disabled (hidden)'
                ),
                'attr' => array(
                    
                )                
            ) )
*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Five\BloggerBlogBundle\Entity\Blog',
            'required'   => false,
            'form_layout' => 'horizontal',
                'attr' => array(
                    
                )            
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'five_blog';
    }
}
