<?php

namespace Five\AdminBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface,
    Symfony\Component\Form\Exception\UnexpectedTypeException,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @author David ALLIX
 */
class GedmoTranslationMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        if (null === $data || array() === $data) {
            return;
        }

        if (!is_array($data) && !is_object($data)) {
            //throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        foreach ($forms as $translationsFieldsForm) {
            $transName = $translationsFieldsForm->getConfig()->getName();
            $translationsFieldsConfig = $translationsFieldsForm->getConfig();
            //$translationClass = $translationsFieldsConfig->getOption('personal_translation');
            //
            $translatableClass = $translationsFieldsForm->getParent()->getConfig()->getOption( 'personal_translation' );
            $exp = explode( '_', $transName );

            //if( is_array( $exp ) )
            //{
                $locale = $exp[1];
                $transFieldName = $exp[0];            
//            $locale = $translationsFieldsForm->getConfig()->getName();

            //var_dump($transName.'// ');

            $tmpFormData = array();
            foreach ($data as $translation) {
                if ($locale === $translation->getLocale()) {
                    $tmpFormData[$translation->getField()] = $translation->getContent();
                    
                    //$translationsFieldsForm->setData( new $translatableClass( $locale, $transFieldName, $translation->getContent() ) );
                }
            }
            if(!empty( $tmpFormData ))
            {
                $translationsFieldsForm->setData($tmpFormData);
            }
            //var_dump( $tmpFormData );
            
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        if (null === $data) {
            return;
        }

        if (!is_array($data) && !is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        $newData = new ArrayCollection();
        //var_dump( $forms->current()->getConfig()->getOption( 'personal_translation' ) );
        foreach ($forms as $translationsFieldsForm) {
            $translationsFieldsConfig = $translationsFieldsForm->getConfig();
            //var_dump( $translationsFieldsForm->getConfig()->getOption( 'field' ) );
            $transName = $translationsFieldsConfig->getName();
            $exp = explode( '_', $transName );

            //if( is_array( $exp ) )
            //{
                $locale = $exp[1];
                $transFieldName = $exp[0];
            //}

            //$locale = $translationsFieldsConfig->getName();
            $translatableClass = $translationsFieldsForm->getParent()->getConfig()->getOption( 'personal_translation' );
            //var_dump( '-----',$transName, $transFieldName, '-----' );
            
            $transForm = array(
                $transFieldName => $translationsFieldsForm->getData()
            );

            //print_r( $translationsFieldsForm->getData() );
///            $translationsFieldsForm
//echo  $locale;
            foreach ($transForm as $field => $content) {

                $existingTranslation = $data ? $data->filter(function($object) use ($locale, $field) {
                    return ($object && ($object->getLocale() === $locale) && ($object->getField() === $field));
                })->first() : null;

                if ($existingTranslation) {
                    $existingTranslation->setContent($content);
                    //$newData->add($existingTranslation);
                    $data[] = $existingTranslation;

                } else {
                    $translation = new $translatableClass($locale, $field, $content);
                    //var_dump( 'NEWTRANS', $content );
/*                    $translation->setLocale($locale);
                    $translation->setField($field);
                    $translation->setContent($content);*/
                    //var_dump( 'CountPRE->'.count( $data ) );
                    //$data->add($translation);
                    $data[] = $translation;
                    //var_dump( 'CountPPOST->'.count( $data ) );
                }
            }
        }
//var_dump( 'CountTransData->'.count( $data ) );
        //$data[] = $newData;
    }
}
