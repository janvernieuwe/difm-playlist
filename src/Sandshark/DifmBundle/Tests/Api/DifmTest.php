<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests\Api;

use Sandshark\DifmBundle\Collection\ChannelCollection;
use Sandshark\DifmBundle\Entity\Channel;
use Sandshark\DifmBundle\Tests\DifmWebTestCase;

class DifmWebTest extends DifmWebTestCase
{

    /**
     * @var ChannelCollection
     */
    private static $channels;

    public static function setUpBeforeClass()
    {
        $client = parent::createClient();
        $difm = $client->getContainer()
            ->get('channel_difm');
        self::$channels = $difm->getChannels();
    }

    public function testChannelCount()
    {
        $this->assertEquals(84, count(self::$channels));
    }

    public function testChannelInstances()
    {
        foreach (self::$channels as $channel) {
            $this->assertInstanceOf('Sandshark\DifmBundle\Entity\Channel', $channel);
        }
    }

    public function testFirstChannel()
    {
        /** @var Channel $channel */
        $channel = array_values(self::$channels->getArrayCopy())[0];
        $this->assertEquals('00sclubhits', $channel->getChannelKey());
        $this->assertEquals('00\'s Club Hits', $channel->getChannelName());
        $this->assertEquals(
            'http://listen.di.fm/steamlist/00sclubhits.pls',
            $channel->getChannelPlaylist()
        );
        $this->assertEquals(324, $channel->getChannelId());
    }

    public function testSecondChannel()
    {
        /** @var Channel $channel */
        $channel = array_values(self::$channels->getArrayCopy())[1];
        $this->assertEquals('ambient', $channel->getChannelKey());
        $this->assertEquals('Ambient', $channel->getChannelName());
        $this->assertEquals(
            'http://listen.di.fm/steamlist/ambient.pls',
            $channel->getChannelPlaylist()
        );
        $this->assertEquals(12, $channel->getChannelId());
    }

    public function testLastChannel()
    {
        $index = count(self::$channels) - 1;
        /** @var Channel $channel */
        $channel = array_values(self::$channels->getArrayCopy())[$index];
        $this->assertEquals('vocaltrance', $channel->getChannelKey());
        $this->assertEquals('Vocal Trance', $channel->getChannelName());
        $this->assertEquals(
            'http://listen.di.fm/steamlist/vocaltrance.pls',
            $channel->getChannelPlaylist()
        );
        $this->assertEquals(2, $channel->getChannelId());
    }
}
