<?php
namespace Omma\AppBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class AbstractAuthenticatedTest extends WebTestCase
{

    private static $fixtureLoaded = false;

    protected function login($username)
    {
        $userManager = $this->getContainer()->get("omma.user.orm.user_manager");
        $user = $userManager->findUserByUsername($username);
        
        $this->loginAs($user, "user");
    }

    public function setUp()
    {
        if (! self::$fixtureLoaded) {
            $fixtureManager = $this->getContainer()->get("h4cc_alice_fixtures.manager");
            $fixtureManager->load(require (__DIR__ . "/../DataFixtures/Alice/OmmaDataFixtureSet.php"));
            self::$fixtureLoaded = true;
        }
        
        $this->login("admin");
    }

    public function pushContent($path, $parameters, $method = 'POST', $authentication = false, $success = true)
    {
        $client = $this->makeClient($authentication);
        $client->request($method, $path, $parameters);
        
        $content = $client->getResponse()->getContent();
        if (is_bool($success)) {
            $this->isSuccessful($client->getResponse(), $success);
        }
        
        return $content;
    }
}
