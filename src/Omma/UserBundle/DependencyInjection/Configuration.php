<?php

namespace Omma\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\ExpressionLanguage\Node\ArrayNode;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('omma_user');

        $this->ldapConfig($rootNode);

        return $treeBuilder;
    }

    private function ldapConfig(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode("ldap")
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode("hostname")->isRequired()->end()
                        ->scalarNode("port")->end()
                        ->scalarNode("security")->end()
                        ->scalarNode("bindName")->end()
                        ->scalarNode("bindPassword")->end()
                        ->scalarNode("baseDn")->end()
                        ->arrayNode("users")
                            ->cannotBeEmpty()
                            ->children()
                                ->scalarNode("dn")->isRequired()->end()
                                ->scalarNode("filter")->defaultValue("(&(|(objectClass=organizationalPerson)(objectClass=inetOrgPerson)))")->end()
                                ->arrayNode("mapping")
                                    ->children()
                                        ->scalarNode("username")->isRequired()->end()
                                        ->scalarNode("email")->end()
                                        ->scalarNode("firstname")->end()
                                        ->scalarNode("lastname")->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode("groups")
                            ->cannotBeEmpty()
                            ->children()
                                ->scalarNode("dn")->isRequired()->end()
                                ->scalarNode("filter")->defaultValue("(objectClass=posixGroup)")->end()
                                ->arrayNode("mapping")
                                    ->children()
                                        ->scalarNode("name")->isRequired()->end()
                                        ->scalarNode("members")->isRequired()->end()
                                    ->end()
                                ->end()
                            ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
