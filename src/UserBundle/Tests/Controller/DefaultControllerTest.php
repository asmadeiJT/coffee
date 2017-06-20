<?php

namespace UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testAddUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('a.add-user')
            ->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Add new user', $crawler->filter('h2')->text());

        $form = $crawler->selectButton('add_user_submit')->form();
        $crawler = $client->submit($form, array(
            'add_user[Name]' => 'Fabien',
            'add_user[Type]' => 'owner',
            'add_user[Amortization]' => 0
        ));

        $this->assertContains('/', $crawler->text());
    }
}
