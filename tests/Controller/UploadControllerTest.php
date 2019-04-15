<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UploadControllerTest extends WebTestCase
{
    /**
     * Test to make sure that the registration page is working
     */
    public function testUploadSpreadsheet()
    {
        $client = static::createClient();
        $client->request('GET', '/upload/spreadsheet');

        // Check to make sure the page is loading correctly with a 200 HTTP Response code.
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
