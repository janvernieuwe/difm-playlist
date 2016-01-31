<?php

namespace DifmBundle\Playlist;

class M3u extends AbstractPlaylist implements PlaylistInterface
{
    /**
     * Generates a .m3u playlist.
     * @param null $data
     * @return string
     */
    public function render($data = null)
    {
        $lines = [];
        $lines[] = '#EXTM3U';
        foreach ($this->channels as $channel) {
            $lines[] = sprintf('#EXTINF:-1,%s', $channel->getChannelName());
            $lines[] = $channel->getStreamUrl($this->premium, $this->listenKey);
        }
        $data = implode("\n", $lines);
        return parent::render($data);
    }

    /**
     * Return the content type.
     * @return string
     */
    public function getContentType()
    {
        return 'application/x-mpegurl';
    }
}
