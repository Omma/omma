<?php
namespace Omma\AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class MeetingControllerNonAuthenticatedTest extends WebTestCase
{

    public function testCget()
    {
        $this->fetchContent("/meetings", "GET", false, false);
    }

    public function testGet()
    {
        $this->fetchContent("/meetings/1", "GET", false, false);
    }
}
