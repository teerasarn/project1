<?php

namespace Five\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Five\AdminBundle\Form\EventListener\AddTranslatedFieldSubscriber;
use Five\AdminBundle\Form\DataMapper\GedmoTranslationMapper;

class TranslatedFieldType extends AbstractType
{
    protected $container;
    protected $options;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;

        if(! class_exists($options['personal_translation']))
        {
            Throw new \InvalidArgumentException(sprintf("Unable to find personal translation class: '%s'", $options['personal_translation']));
        }
        if(! $options['field'])
        {
            Throw new \InvalidArgumentException("You should provide a field to translate");
        }
        $builder->setDataMapper(new GedmoTranslationMapper());
        $subscriber = new AddTranslatedFieldSubscriber($builder->getFormFactory(), $this->container, $options);
        $builder->addEventSubscriber($subscriber);
    }

    public function getDefaultOptions(array $options = array())
    {
        $options['remove_empty'] = false; //Personal Translations without content are removed
        $options['csrf_protection'] = false; 
        $options['personal_translation'] = null; //Personal Translation class
        $options['locales'] = array('en', 'fr'); //the locales you wish to edit
        $options['required_locale'] = array('en'); //the required locales cannot be blank
        $options['field'] = false; //the field that you wish to translate
        $options['widget'] = "text"; //change this to another widget like 'texarea' if needed
        $options['entity_manager_removal'] = true; //auto removes the Personal Translation thru entity manager
        $options[ 'field_label' ] = false;

        return $options;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        //var_dump($this->options['personal_translation']);
        $resolver->setDefaults( array(
        'data_class'      => null,
        //'Five\AdminBundle\Entity\Translation',
        'required'        => false,
        'auto_initialize' => false,
        'remove_empty' => false, //Personal Translations without content are removed
        'csrf_protection' => false,
        'personal_translation' => null, //Personal Translation class
        'locales' => array('fr'), //the locales you wish to edit
        'required_locale' => array('en'), //the required locales cannot be blank
        'field' => null, //the field that you wish to translate
        'widget' => "text", //change this to another widget like 'texarea' if needed
        'entity_manager_removal' => true, //auto removes the Personal Translation thru entity manager            
        'field_label' => false
        ));
    }

    public function getName()
    {
        return 'translatable_field';
    }
}