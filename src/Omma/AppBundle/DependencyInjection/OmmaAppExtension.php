<?php

namespace Omma\AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OmmaAppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . "/../Resources/config"));
        $loader->load("services.yml");
        $loader->load("orm.yml");

        // fallback to locales without territory (e.g. en, de)
        // available locales will look like en_US, en, de_DE, de
        $availableLocales = array();
        foreach ($config['languages'] as $language) {
            $availableLocales[] = $language;
            // if language contains an underscore
            if (false !== ($pos = strpos($language, "_"))) {
                $availableLocales[] = substr($language, 0, $pos);
                continue;
            }
        }

        $container->setParameter("omma.languages", $availableLocales);
    }
}
