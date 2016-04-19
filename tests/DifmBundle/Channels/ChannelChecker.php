<?php

namespace DifmBundle\Tests\Api;

use DifmBundle\Api\Channels;
use DifmBundle\Entity\Channel;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ConnectionChecker.
 */
class ConnectionChecker extends WebTestCase
{
    /**
     * @var Container
     */
    private static $container;

    /**
     * Difm channel provider.
     *
     * @var Channels
     */
    private static $difm;

    /**
     * Radio Tunes channel provider.
     *
     * @var Channels
     */
    private static $radioTunes;

    /**
     * Jazz Radio channel provider.
     *
     * @var Channels
     */
    private static $jazzRadio;

    /**
     * Rock Radio channel provider.
     *
     * @var Channels
     */
    private static $rockRadio;

    /**
     * @var Client
     */
    private static $client;

    /**
     * Premium listenKey.
     *
     * @var string
     */
    private static $key;

    /**
     * @var bool
     *           only check the first channel
     */
    private static $single = false;

    public static function setUpBeforeClass()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        static::$container = static::$kernel->getContainer();
        static::$difm = static::$container->get('difm.channels');
        static::$radioTunes = static::$container->get('radiotunes.channels');
        static::$jazzRadio = static::$container->get('jazzradio.channels');
        static::$rockRadio = static::$container->get('rockradio.channels');
        self::$client = new Client();
        self::$key = self::$container->getParameter('listenKey');
    }

    public function testDifm()
    {
        $channels = self::$difm->loadChannels();
        foreach ($channels as $channel) {
            foreach (['_hi','','_aac','_aacp'] as $quality) {
                $this->checkConnection($channel, true, $quality);
                if (self::$single) {
                    break;
                }
            }
        }
    }

    /**
     * @param Channel $channel
     * @param $premium
     * @param $quality
     */
    private function checkConnection(Channel $channel, $premium, $quality)
    {
        $url = $channel->getStreamUrl($premium, $premium ? self::$key : '', $quality);
        $promise = self::$client->getAsync($url, ['stream' => true, 'timeout' => 3]);
        $promise
            ->then(
                function (ResponseInterface $response) use ($premium, $channel, $quality) {
                    $strPremium = $premium ? ' (premium)' : '';
                    $this->assertEquals(
                        200,
                        (int)$response->getStatusCode(),
                        sprintf(
                            '[FAIL] %s %s%s %s',
                            $channel->getDomain(),
                            $channel->getChannelName(),
                            $strPremium,
                            $quality
                        )
                    );
                    if (200 === $response->getStatusCode()) {
                        echo sprintf(
                            '[PASS] %s %s%s %s',
                            $channel->getDomain(),
                            $channel->getChannelName(),
                            $strPremium,
                            $quality
                        );
                    }
                    echo PHP_EOL;
                }
            );
    }

    public function testRadioTunes()
    {
        $channels = self::$radioTunes->loadChannels();
        foreach ($channels as $channel) {
            foreach (['','_aac','_aacp'] as $quality) {
                $this->checkConnection($channel, true, $quality);
                if (self::$single) {
                    break;
                }
            }
        }
    }

    public function testJazzRadio()
    {
        $channels = self::$jazzRadio->loadChannels();
        foreach ($channels as $channel) {
            foreach (['','_aac','_aacp'] as $quality) {
                $this->checkConnection($channel, true, $quality);
                if (self::$single) {
                    break;
                }
            }
        }
    }

    public function testRockRadio()
    {
        $channels = self::$rockRadio->loadChannels();
        foreach ($channels as $channel) {
            foreach (['','_aac','_aacp'] as $quality) {
                $this->checkConnection($channel, true, $quality);
                if (self::$single) {
                    break;
                }
            }
        }
    }
}
