<?php

namespace Sandshark\DifmBundle\Tests\Controller;

use Sandshark\DifmBundle\Tests\DifmWebTestCase;

class DefaultControllerWebTest extends DifmWebTestCase
{

    public function testPls()
    {
        $client = static::createClient();
        $client->request('GET', '/test.pls');
        $this->assertContains('[playlist]', $client->getResponse()->getContent());
        $this->assertContains('NumberOfEntries', $client->getResponse()->getContent());
        $this->assertContains('File1', $client->getResponse()->getContent());
        $this->assertContains('Title1', $client->getResponse()->getContent());
        $this->assertContains('Length1', $client->getResponse()->getContent());
        $this->assertContains('?test', $client->getResponse()->getContent());
    }

    public function testM3u()
    {
        $client = static::createClient();
        $client->request('GET', '/test.m3u');
        $this->assertContains('#EXTM3U', $client->getResponse()->getContent());
        $this->assertContains('#EXTINF:-1,', $client->getResponse()->getContent());
        $this->assertContains('http://prem2.di.fm:80', $client->getResponse()->getContent());
        $this->assertContains('?test', $client->getResponse()->getContent());
    }

    public function testInvalidFormat()
    {
        $client = static::createClient();
        $client->request('GET', '/test.invalid');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
