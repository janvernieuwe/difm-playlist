<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests;


use Sandshark\DifmBundle\Api\Difm;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DifmWebTestCase extends WebTestCase
{
    /**
     * Mock Guzzle service and api before returning the client
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    public static function createClient()
    {
        $instance = new self();
        $data = file_get_contents(__DIR__ . '/MockResponse/channels.json');
        $response = $instance->getMockBuilder('GuzzleHttp\Message\FutureResponse')
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->method('getBody')
            ->willReturn($data);

        $guzzle = $instance->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $guzzle
            ->method('get')
            ->willReturn($response);

        $client = parent::createClient();
        $container = $client->getContainer();
        $container->set('sandshark_difm.guzzle', $guzzle);
        $container->set('sandshark_difm.api', new Difm($guzzle, $container->get('sandshark_difm.cache')));
        return $client;
    }

    public function testNull()
    {
    }
}
