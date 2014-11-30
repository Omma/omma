<?php
namespace Omma\AppBundle\Tests\Controller;

use Omma\AppBundle\Tests\AbstractAuthenticatedTest;

class MeetingControllerTest extends AbstractAuthenticatedTest
{

    public function testCget()
    {
        $this->fetchContent("/meetings");
    }

    public function testGet()
    {
        $this->fetchContent("/meetings/1");
    }
}
