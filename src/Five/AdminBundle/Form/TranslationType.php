<?php

namespace Five\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transLocale')
            ->add('messageDomain')
            ->add('transKey')                        
            ->add('translation')
/*            ->add('updatedAt')
            ->add('createdAt')*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Five\AdminBundle\Entity\Translation',
            'required'   => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'five_adminbundle_translation';
    }
}
