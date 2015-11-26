<?php

namespace Blogger\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlogType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('blog')
            ->add('image')
            ->add('tags')
            ->add('created')
            ->add('updated')
            ->add('title_fr')
            ->add('title_en')
            ->add('image_1')
            ->add('image_2')
            ->add('image_text_1_fr')
            ->add('image_text_1_en')
            ->add('image_text_2_fr')
            ->add('image_text_2_en')
            ->add('intro_text_fr')
            ->add('intro_text_en')
            ->add('blog_fr')
            ->add('blog_en')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Blogger\BlogBundle\Entity\Blog'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'blogger_blogbundle_blog';
    }
}
