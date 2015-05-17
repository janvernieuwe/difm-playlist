<?php

namespace Sandshark\DifmBundle\Tests\Controller;

use Sandshark\DifmBundle\Tests\DifmWebTestCase;

class DefaultControllerWebTest extends DifmWebTestCase
{

    public function testPremiumPls()
    {
        $client = static::createClient();
        $client->request('GET', '/difm/premium/test.pls');
        $this->assertContains('[playlist]', $client->getResponse()->getContent());
        $this->assertContains('NumberOfEntries', $client->getResponse()->getContent());
        $this->assertContains('File1', $client->getResponse()->getContent());
        $this->assertContains('Title1', $client->getResponse()->getContent());
        $this->assertContains('Length1', $client->getResponse()->getContent());
        $this->assertContains('http://prem', $client->getResponse()->getContent());
        $this->assertContains('?test', $client->getResponse()->getContent());
    }

    public function testPublicPls()
    {
        $client = static::createClient();
        $client->request('GET', '/difm/public/test.pls');
        $this->assertContains('[playlist]', $client->getResponse()->getContent());
        $this->assertContains('NumberOfEntries', $client->getResponse()->getContent());
        $this->assertContains('File1', $client->getResponse()->getContent());
        $this->assertContains('Title1', $client->getResponse()->getContent());
        $this->assertContains('Length1', $client->getResponse()->getContent());
        $this->assertContains('http://pub', $client->getResponse()->getContent());
        $this->assertContains('?test', $client->getResponse()->getContent());
    }

    public function testPremiumM3u()
    {
        $client = static::createClient();
        $client->request('GET', '/difm/premium/test.m3u');
        $this->assertContains('#EXTM3U', $client->getResponse()->getContent());
        $this->assertContains('#EXTINF:-1,', $client->getResponse()->getContent());
        $this->assertContains('http://prem', $client->getResponse()->getContent());
        $this->assertContains('?test', $client->getResponse()->getContent());
    }

    public function testPublicM3u()
    {
        $client = static::createClient();
        $client->request('GET', '/difm/public/test.m3u');
        $this->assertContains('#EXTM3U', $client->getResponse()->getContent());
        $this->assertContains('#EXTINF:-1,', $client->getResponse()->getContent());
        $this->assertContains('http://pub', $client->getResponse()->getContent());
        $this->assertContains('?test', $client->getResponse()->getContent());
    }

    public function testInvalidFormat()
    {
        $client = static::createClient();
        $client->request('GET', '/difm/premium/test.invalid');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
