<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        date_default_timezone_set( 'America/Montreal' );
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Five\CuisinesVerdunBundle\FiveCuisinesVerdunBundle(),
            new Five\CVApiBundle\FiveCVApiBundle(),
            new SunCat\MobileDetectBundle\MobileDetectBundle(),


            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Five\MailerBundle\FiveMailerBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            //new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Five\CoreBundle\FiveCoreBundle(),
            new Prezent\Doctrine\TranslatableBundle\PrezentDoctrineTranslatableBundle(),
            //new BeSimple\I18nRoutingBundle\BeSimpleI18nRoutingBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            //new Lexik\Bundle\TranslationBundle\LexikTranslationBundle(),
            new Five\AdminBundle\FiveAdminBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            
            //=========*** blog bundle ***===============
            new Blogger\BlogBundle\BloggerBlogBundle(),
            //============ CKEditor + Upload Images feature ==========
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            
            // ===== editor ==========
            //new Sonata\MarkItUpBundle\SonataMarkItUpBundle(),
            //new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            //new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            //new Sonata\FormatterBundle\SonataFormatterBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
