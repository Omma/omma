<?php
namespace Omma\AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 *
 * @author Adrian Woeltche
 */
class MeetingControllerNonAuthenticatedTest extends WebTestCase
{

    public function testCget()
    {
        $this->fetchContent("/meetings.json", "GET", false, false);
    }

    public function testGet()
    {
        $this->fetchContent("/meetings/1.json", "GET", false, false);
    }
}
