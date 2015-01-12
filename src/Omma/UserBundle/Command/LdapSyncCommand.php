<?php
namespace Omma\UserBundle\Command;

use Omma\UserBundle\Ldap\LdapSyncService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sync users from LDAP directory with database.
 * Users and Groups which are not present in the database will be added,
 * Users and Groups which are present in ldap an database will be updated,
 * Users and Groups which were synced previously will be deleted or marked as deleted.
 *
 * This Command is a frontend to the {@link Omma\UserBundle\Ldap\LdapSyncService}
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
