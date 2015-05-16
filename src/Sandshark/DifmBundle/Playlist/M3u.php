<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:49
 */

namespace Sandshark\DifmBundle\Playlist;

use Sandshark\DifmBundle\Entity\Channel;

/**
 * Class Pls
 * @package Sandshark\DifmBundle\Playlist
 */
class M3u extends AbstractPlaylist implements PlaylistInterface
{
    /**
     * Generates a .m3u playlist
     * @return string
     * @throws PlaylistException
     */
    public function render()
    {
        $lines = array();
        $lines[] = '#EXTM3U';
        /** @var Channel $channel $channel */
        foreach ($this->channels as $channel) {
            $lines[] = sprintf('#EXTINF:-1,%s', $channel->getChannelName());
            $lines[] = $this->getStreamUrl($channel);
        }
        return implode("\n", $lines);
    }

    /**
     * Return the content type
     * @return string
     */
    public function getContentType()
    {
        return 'application/x-mpegurl';
    }
}
