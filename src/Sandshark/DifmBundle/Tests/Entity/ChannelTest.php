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

    public function testInvalidNameNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelName(null);
    }

    public function testInvalidNameEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelName('');
    }

    public function testInvalidKeyNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelKey(null);
    }

    public function testInvalidKeyEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelKey('');
    }

    public function testInvalidPlaylistNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelPlaylist(null);
    }

    public function testInvalidPlaylistEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelPlaylist('');
    }

    public function testInvalidIdNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelId(null);
    }

    public function testInvalidIdString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = new Channel();
        $channel->setChannelId('');
    }
}
