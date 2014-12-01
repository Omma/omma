<?php
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MeetingControllerTest extends WebTestCase
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
