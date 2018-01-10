<?php

namespace App\Test\Controller;

ob_start();

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreControllerTest extends WebTestCase
{
    public function testIndexIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testShowFormIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(1, $crawler->filter('html:contains("Louvre Tickets Office")')->count());
        $this->assertSame(2, $crawler->filter('input[type=date]')->count());
        $this->assertSame(2, $crawler->filter('input[type=radio]')->count());
        $this->assertSame(1, $crawler->filter('select')->count());
        $this->assertSame(1, $crawler->filter('input[type=checkbox]')->count());
        $this->assertSame(1, $crawler->filter('input[type=submit]')->count());
        $this->assertSame(1, $crawler->filter('a#add_tag_link')->count());
        $this->assertSame(1, $crawler->filter('a#remove_tag_link')->count());
    }

    public function testAddNewTicketIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Add To Cart')->form();

        $form['reservation[bookingDate]'] = '2028-08-08';
        $form['reservation[visitType]'] = 'fullDay';
        $form['reservation[email]'] = 'john.doe@mail.com';
        $form['reservation[tickets][0][guest][firstName]'] = 'john';
        $form['reservation[tickets][0][guest][lastName]'] = 'Doe';
        $form['reservation[tickets][0][guest][dateOfBirth]'] = '01/01/1950';
        $form['reservation[tickets][0][guest][country]'] = 'FR';
        $form['reservation[tickets][0][reducedPrice]'] = 1;
        
        $client->submit($form);
    }
}