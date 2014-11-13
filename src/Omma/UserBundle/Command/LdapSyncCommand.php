<?php
namespace Omma\UserBundle\Command;

use Omma\UserBundle\Ldap\LdapSyncService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapSyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("omma:user:ldap-sync")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LdapSyncService $syncService */
        $syncService = $this->getContainer()->get("omma.user.ldap.sync_service");
        $syncService->sync();
    }
}
