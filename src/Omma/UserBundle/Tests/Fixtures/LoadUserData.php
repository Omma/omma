<?php
namespace Omma\UserBundle\Tests\Fixtures;

use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 *
 *
 * @author Florian Pfitzer <pfitzer@w3p.cc>
 */
class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $test1 = new User();
        $test1->setUsername("test1");
        $test1->setEmail("test-foo@omma.local");
        $test1->setLdapId("uid=test1,ou=users,dc=omma,dc=local");
        $test1->setPlainPassword("test");

        $test3 = new User();
        $test3->setUsername("test3");
        $test3->setEmail("test3@omma.local");
        $test3->setLdapId("uid=test3,ou=users,dc=omma,dc=local");
        $test3->setPlainPassword("test3");
        $test3->setEnabled(true);

        $test4 = new User();
        $test4->setUsername("test4");
        $test4->setEmail("test4@omma.local");
        $test4->setPlainPassword("test4");
        $test4->setEnabled(true);

        $test5 = new User();
        $test5->setUsername("test5");
        $test5->setEmail("test5@omma.local");
        $test5->setPlainPassword("test5");
        $test5->setEnabled(true);

        // groups
        $group = new Group("admin");
        $test1->addGroup($group);
        $test3->addGroup($group);
        $test4->addGroup($group);
        $manager->persist($group);

        $group = new Group("marketing");
        $group->setLdapId("cn=marketing,ou=groups,dc=omma,dc=local");
        $manager->persist($group);

        $manager->persist($test1);
        $manager->persist($test3);
        $manager->persist($test4);

        $manager->flush();
    }
}
