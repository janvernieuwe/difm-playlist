<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:45
 */

namespace Sandshark\DifmBundle\Playlist;

use Sandshark\DifmBundle\Collection\ChannelCollection;
use Sandshark\DifmBundle\Entity\Channel;

/**
 * Interface PlaylistInterface
 * @package Sandshark\DifmBundle\Playlist
 */
interface PlaylistInterface
{
    /**
     * Class constructor
     * @param ChannelCollection $channels
     * @param string $listenKey
     */
    public function __construct(ChannelCollection $channels);

    /**
     * Generate the playlist and return the string
     * @return string
     */
    public function render();

    /**
     * Return the content type for the current playlist type
     * @return string
     */
    public function getContentType();

    /**
     * Get the file name for the current playlist type
     * @return mixed
     */
    public function getFileName();

    /**
     * Set the listen key
     * @return mixed
     */
    public function setListenKey($listenKey);

    /**
     * Set premium toggle
     * @return mixed
     */
    public function setPremium($premium);

    /**
     * Set the streaming quality
     * @return mixed
     */
    public function setQuality($quality);

    /**
     * Get the stream url
     * @return mixed
     */
    public function getStreamUrl(Channel $channel);

}
