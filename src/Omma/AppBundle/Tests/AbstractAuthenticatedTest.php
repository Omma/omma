<?php
namespace Omma\AppBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class AbstractAuthenticatedTest extends WebTestCase
{

    private static $fixtureLoaded = false;

    public function setUp()
    {
        if (! self::$fixtureLoaded) {
            $fixtureManager = $this->getContainer()->get("h4cc_alice_fixtures.manager");
            $fixtureManager->load(require (__DIR__ . "/../DataFixtures/Alice/OmmaDataFixtureSet.php"));
            self::$fixtureLoaded = true;
        }
        
        $authentication = $this->getContainer()->getParameter("liip_functional_test.authentication");
        $userManager = $this->getContainer()->get("omma.user.orm.user_manager");
        $user = $userManager->findUserByUsername($authentication["username"]);
        
        $this->loginAs($user, "user");
    }
}
