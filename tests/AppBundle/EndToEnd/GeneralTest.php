<?php

namespace Tests\AppBundle\EndToEnd;

class GeneralTest extends BaseBrowserTestCase
{
    public function testDeutschlandfunk()
    {
        // This is Mink's Session.
        $session = $this->getSession();

        // Go to a page.
        $session->visit('http://www.deutschlandfunk.de/');

        // Validate text presence on a page.
        $this->assertTrue($session->getPage()->hasContent('Deutschlandfunk'));
    }

    public function testUsingSession()
    {
        // This is Mink's Session.
        $session = $this->getSession();

        // Go to a page.
        $session->visit($this->getBrowser()->getBaseUrl() . 'survey/');

        // Validate text presence on a page.
        $this->assertTrue($session->getPage()->hasContent('Surveythor'));
    }
}