<?php
namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenderType extends AbstractType
{
    private $genderChoices;

    public function __construct( array $genderChoices )
    {
        $this->genderChoices = $genderChoices;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'choices' => $this->genderChoices
        );
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'choices' => $this->genderChoices
        ) );
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'gender';
    }
}