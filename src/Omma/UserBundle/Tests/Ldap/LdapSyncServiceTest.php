<?php
namespace Omma\UserBundle\Tests\Ldap;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Omma\UserBundle\Ldap\LdapSyncService;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LdapSyncServiceTest extends WebTestCase
{
    public function testSync()
    {
        $this->loadFixtures(array(
            '\Omma\UserBundle\Tests\Fixtures\LoadUserData'
        ));

        $userManager = $this->getContainer()->get("omma.user.orm.user_manager");
        $groupManager = $this->getContainer()->get("omma.user.orm.group_manager");

        $syncService = new LdapSyncService(new TestLdapDirectory(), $userManager, $groupManager);
        $syncService->sync();

        // test1 already exists, email address should be upgraded
        /** @var User $user1 */
        $user1 = $userManager->findUserByUsername("test1");
        $this->assertNotNull($user1, "test1 does not exist anymore");
        $this->assertEquals("test1@omma.local", $user1->getEmail());
        $this->assertTrue($user1->isEnabled());

        // test2 doesn't exist, should be created
        /** @var User $user2 */
        $user2 = $userManager->findUserByUsername("test2");
        $this->assertNotNull($user2, "test2 not created");
        $this->assertEquals("test2@omma.local", $user2->getEmail());

        // test3 exists in db, but not in ldap, should be disabled
        /** @var User $user3 */
        $user3 = $userManager->findUserByUsername("test3");
        $this->assertNotNull($user3, "test3 does not exist anymore");
        $this->assertFalse($user3->isEnabled());

        // test4 exists in db, but not in ldap and is no ldap user, should not be disabled
        /** @var User $user4 */
        $user4 = $userManager->findUserByUsername("test4");
        $this->assertNotNull($user4, "test4 does not exist anymore");
        $this->assertTrue($user4->isEnabled());

        // test5 exists in ldap and db, but has no ldap id set
        /** @var User $user5 */
        $user5 = $userManager->findUserByUsername("test5");
        $this->assertNotNull($user5, "test4 does not exist anymore");
        $this->assertTrue($user5->isEnabled());
        $this->assertEquals("uid=test5,ou=users,dc=omma,dc=local", $user5->getLdapId());

        //
        // groups
        //

        // group admin exists, with members test1, test3, test4. Should be test1, test2, test4. (test4 not in ldap)
        /** @var Group $group */
        $group = $groupManager->findGroupByName("admin");
        $this->assertNotNull($group, "group 'admin' does not exist anymore");
        $this->assertEquals("cn=admin,ou=groups,dc=omma,dc=local", $group->getLdapId());
        $this->assertTrue(in_array("admin", $user1->getGroupNames()));
        $this->assertTrue(in_array("admin", $user2->getGroupNames()));
        $this->assertFalse(in_array("admin", $user3->getGroupNames()));
        $this->assertTrue(in_array("admin", $user4->getGroupNames()));

        // group it doesn't exist.
        $group = $groupManager->findGroupByName("it");
        $this->assertNotNull($group, "group 'it' does not exist anymore");
        $this->assertEquals("cn=it,ou=groups,dc=omma,dc=local", $group->getLdapId());

        // group marketing exists in db, but not in ldap. Should be deleted
        $group = $groupManager->findGroupByName("marketing");
        $this->assertNull($group);

    }
}
