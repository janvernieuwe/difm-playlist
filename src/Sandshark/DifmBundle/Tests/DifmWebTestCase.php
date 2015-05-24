<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests;

use Sandshark\DifmBundle\Api\ChannelProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DifmWebTestCase
 * @package Sandshark\DifmBundle\Tests
 */
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

        /** @noinspection PhpUndefinedMethodInspection */
        $response
            ->method('getBody')
            ->willReturn($data);

        $guzzle = $instance->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        /** @noinspection PhpUndefinedMethodInspection */
        $guzzle
            ->method('get')
            ->willReturn($response);

        /** @noinspection PhpUndefinedMethodInspection */
        $guzzle
            ->method('getBaseUrl')
            ->willReturn('http://listen.di.fm');

        $client = parent::createClient();
        $container = $client->getContainer();
        $container->set('api_difm', $guzzle);
        /** @noinspection PhpParamsInspection */
        $container->set(
            'channel_difm',
            new ChannelProvider(
                $container,
                $guzzle
            )
        );
        return $client;
    }

    public function testNull()
    {
    }
}
