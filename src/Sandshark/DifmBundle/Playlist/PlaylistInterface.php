<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:45
 */

namespace Sandshark\DifmBundle\Playlist;

use Sandshark\DifmBundle\Collection\ChannelCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface PlaylistInterface
 * @package Sandshark\DifmBundle\Playlist
 */
interface PlaylistInterface
{
    /**
     * Class constructor
     * @param ChannelCollection $channels
     */
    public function __construct(ChannelCollection $channels);

    /**
     * Generate the playlist and return the string
     * @param null $data
     * @return Response
     */
    public function render($data = null);

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
     * @param $listenKey
     * @return mixed
     */
    public function setListenKey($listenKey);

    /**
     * Set premium toggle
     * @param $premium
     * @return mixed
     */
    public function setPremium($premium);

    /**
     * Set the streaming quality
     * @param $quality
     * @return mixed
     */
    public function setQuality($quality);

    /**
     * Set the site for the filename
     * @param string $site
     * @return
     **/
    public function setSite($site);
}
