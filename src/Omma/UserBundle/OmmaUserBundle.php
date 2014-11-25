<?php

namespace Omma\UserBundle;

use Omma\UserBundle\Security\LdapSecurityFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OmmaUserBundle extends Bundle
{
    public function getParent()
    {
        return "SonataUserBundle";
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var SecurityExtension $extension */
        $extension = $container->getExtension("security");
        $extension->addSecurityListenerFactory(new LdapSecurityFactory());
    }
}
