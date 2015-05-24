<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:49
 */

namespace Sandshark\DifmBundle\Playlist;

/**
 * Class Pls
 * @package Sandshark\DifmBundle\Playlist
 */
class M3u extends AbstractPlaylist implements PlaylistInterface
{
    /**
     * Generates a .m3u playlist
     * @param null $data
     * @return string
     */
    public function render($data = null)
    {
        $lines = array();
        $lines[] = '#EXTM3U';
        foreach ($this->channels as $channel) {
            $lines[] = sprintf('#EXTINF:-1,%s', $channel->getChannelName());
            $lines[] = $channel->getStreamUrl($this->premium, $this->listenKey);
        }
        $data = implode("\n", $lines);
        return parent::render($data);
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
