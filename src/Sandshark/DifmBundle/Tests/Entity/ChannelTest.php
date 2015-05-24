<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests\Entity;

use Sandshark\DifmBundle\Entity\Channel;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class ChannelTest
 * @package Sandshark\DifmBundle\Tests\Entity
 */
class ChannelTest extends TestCase
{

    public function testValidParams()
    {
        $channelName = 'Valid channel name';
        $channelKey = 'validchannelkey';
        $channelPlaylist = 'http://example.com/validplaylist.pls';
        $channelId = 42;

        $channel = new Channel();
        $channel->setChannelId($channelId);
        $channel->setChannelKey($channelKey);
        $channel->setChannelName($channelName);
        $channel->setChannelPlaylist($channelPlaylist);

        $this->assertEquals($channelId, $channel->getChannelId());
        $this->assertEquals($channelKey, $channel->getChannelKey());
        $this->assertEquals($channelName, $channel->getChannelName());
        $this->assertEquals($channelPlaylist, $channel->getChannelPlaylist());
        $this->assertEquals(null, $channel->getId());
    }
}
