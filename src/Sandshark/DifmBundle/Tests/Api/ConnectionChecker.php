<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 23/05/15
 * Time: 21:27
 */

namespace Sandshark\DifmBundle\Tests\Api;

use GuzzleHttp\Client;
use Sandshark\DifmBundle\Api\ChannelProvider;
use Sandshark\DifmBundle\Entity\Channel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class ConnectionChecker extends WebTestCase
{

    /**
     * Sleep after a request
     * @var int
     */
    const SLEEP = 1;

    /**
     * @var Container
     */
    private static $container;

    /**
     * Difm channel provider
     * @var ChannelProvider
     */
    private static $difm;

    /**
     * Radio Tunes channel provider
     * @var ChannelProvider
     */
    private static $radioTunes;

    /**
     * @var Client
     */
    private static $client;

    /**
     * Premium listenKey
     * @var string
     */
    private static $key;

    public static function setUpBeforeClass()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        static::$container = static::$kernel->getContainer();
        static::$difm = static::$container->get('channel_difm');
        static::$radioTunes = static::$container->get('channel_radiotunes');
        self::$client = new Client();
        self::$key = static::$container->getParameter('premium_key');
        if (empty(self::$key)) {
            throw new \Exception('Premium key is not set!');
        }
        if (self::SLEEP < 1) {
            throw new \Exception('Minimum sleep time is 1 second, to not DOS the service, %d set', self::SLEEP);
        }
    }

    private function checkConnection(Channel $channel, $premium)
    {
        $url = $channel->getStreamUrl($premium, $premium ? self::$key : '');
        $stream = self::$client->get($url, ['stream' => true]);
        $strPremium = $premium ? ' (premium)' : '';
        $this->assertEquals(
            200,
            (int)$stream->getStatusCode(),
            sprintf(
                '[FAIL] %s %s%s',
                $channel->getDomain(),
                $channel->getChannelName(),
                $strPremium
            )
        );
        if (200 === $stream->getStatusCode()) {
            echo sprintf(
                '[PASS] %s %s%s',
                $channel->getDomain(),
                $channel->getChannelName(),
                $strPremium
            );
        }
        echo PHP_EOL;
        //ob_flush();
        sleep(self::SLEEP);
    }

    public function testDifm()
    {
        $channels = self::$difm->getChannels();
        foreach ($channels as $channel) {
            $this->checkConnection($channel, false);
            $this->checkConnection($channel, true);
        }
    }

    public function testRadioTunes()
    {
        $channels = self::$radioTunes->getChannels();
        foreach ($channels as $channel) {
            $this->checkConnection($channel, false);
            $this->checkConnection($channel, true);
        }
    }
}