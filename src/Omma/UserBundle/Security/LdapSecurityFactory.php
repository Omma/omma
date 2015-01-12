<?php
namespace Omma\UserBundle\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds {@link LdapAuthenticationProvider} and {@link LdapSecurityListener} to symfony security framework
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapSecurityFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = "omma.user.security.authentication_provider.ldap." . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator("omma.user.security.authentication_provider.ldap"))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(2, $id)
        ;

        $listenerId = "omma.user.security.listener.ldap." . $id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator("omma.user.security.listener.ldap"))
        ;

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return "http";
    }

    public function getKey()
    {
        return "ldap";
    }

    public function addConfiguration(NodeDefinition $builder)
    {
    }
}
