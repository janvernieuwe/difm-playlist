<?php

namespace DifmBundle\Playlist;

class Pls extends AbstractPlaylist implements PlaylistInterface
{
    /**
     * Generates a .pls playlist.
     * @param null $data
     * @return string
     */
    public function render($data = null)
    {
        $i = 0;
        $lines = [];
        $lines[] = '[playlist]';
        $lines[] = sprintf('NumberOfEntries=%d', count($this->channels));
        foreach ($this->channels as $channel) {
            ++$i;
            $lines[] = sprintf(
                'File%d=%s',
                $i,
                $channel->getStreamUrl($this->premium, $this->listenKey, $this->quality)
            );
            $lines[] = sprintf('Title%d=%s', $i, $channel->getChannelName());
            $lines[] = sprintf('Length%d=-1', $i);
        }
        $lines[] = 'Version=2';
        $data = implode("\n", $lines);

        return parent::render($data);
    }

    /**
     * Return the content type.
     * @return string
     */
    public function getContentType()
    {
        return 'audio/x-scpls';
    }
}
