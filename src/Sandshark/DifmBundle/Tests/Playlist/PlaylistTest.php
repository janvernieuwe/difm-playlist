<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests\Playlist;

use Sandshark\DifmBundle\Collection\ChannelCollection;
use Sandshark\DifmBundle\Entity\Channel;
use Sandshark\DifmBundle\Playlist\Pls;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class PlaylistTest
 * @package Sandshark\DifmBundle\Tests\Playlist
 */
class PlaylistTest extends TestCase
{

    private $channels;
    private $noChannels;

    public function setUp()
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

        $this->channels = new ChannelCollection();
        $this->channels->append($channel);

        $this->noChannels = new ChannelCollection();
    }

    public function testInvalidListenKeyNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $pls = new Pls($this->channels);
        $pls->setListenKey(null);
    }

    public function testInvalidChannelCollectionEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Pls($this->noChannels);
    }
}
