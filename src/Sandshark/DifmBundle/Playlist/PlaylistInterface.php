<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:45
 */

namespace Sandshark\DifmBundle\Playlist;

use Sandshark\DifmBundle\Collection\ChannelCollection;

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
    public function __construct(ChannelCollection $channels, $listenKey = '');

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
}
