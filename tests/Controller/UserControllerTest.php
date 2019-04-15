<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * Test to make sure that the registration page is working
     */
    public function testRegistration()
    {
        $client = static::createClient();
        $client->request('GET', '/register/');

        // Check to make sure the page is loading correctly with a 200 HTTP Response code.
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->getCrawler();

        // Check to make sure that we have the username input.
        $this->assertEquals(1, $crawler->filter("input[name='fos_user_registration_form[username]']")->count());

        // Check to make sure that we have the email input.
        $this->assertEquals(1, $crawler->filter("input[name='fos_user_registration_form[email]']")->count());

        // Check to make sure that we have the password input.
        $this->assertEquals(1, $crawler->filter("input[name='fos_user_registration_form[plainPassword][first]']")->count());

        // Check to make sure that we have the confirm password input.
        $this->assertEquals(1, $crawler->filter("input[name='fos_user_registration_form[plainPassword][second]']")->count());

        // Check to make sure that we have a submit button
        $this->assertEquals(1, $crawler->filter("form input[type='submit']")->count());
    }
    /**
     * Test to make sure the confirmed page is working.
     */
    public function testConfirmed()
    {
        $client = static::createClient();
        $client->request('GET', '/register/confirmed');

        // Check to make sure we get redirected back to the login page
        // when nobody is logged in.
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
