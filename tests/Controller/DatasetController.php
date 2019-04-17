<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatasetControllerTest extends WebTestCase
{
    /**
     * Test to make sure the describe dataset page is working.
     */
    public function testDescribe()
    {
        $client = static::createClient();
        $client->request('GET', '/dataset/describe/1');

        // Check to make sure we get redirected back to the login page
        // when nobody is logged in.
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
