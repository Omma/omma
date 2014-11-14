<?php
namespace Omma\UserBundle\Security;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapSecurityListener implements ListenerInterface
{
    public function handle(GetResponseEvent $event)
    {
        $event;
    }
}
