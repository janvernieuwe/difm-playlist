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
class Pls extends AbstractPlaylist implements PlaylistInterface
{
    /**
     * Generates a .pls playlist
     * @return string
     * @throws PlaylistException
     */
    public function render()
    {
        $i = 0;
        $lines = array();
        $lines[] = '[playlist]';
        $lines[] = sprintf('NumberOfEntries=%d', count($this->channels));
        /** @var Channel $channel */
        foreach ($this->channels as $channel) {
            $i++;
            $lines[] = sprintf(
                'File%d=%s',
                $i,
                $channel->getStreamUrl($this->premium, $this->listenKey)
            );
            $lines[] = sprintf('Title%d=%s', $i, $channel->getChannelName());
            $lines[] = sprintf('Length%d=-1', $i);
        }
        $lines[] = 'Version=2';
        return implode("\n", $lines);
    }

    /**
     * Return the content type
     * @return string
     */
    public function getContentType()
    {
        return 'audio/x-scpls';
    }
}
