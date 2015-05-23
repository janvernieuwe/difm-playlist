<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:47
 */

namespace Sandshark\DifmBundle\Playlist;

/**
 * Class AbstractPlaylist
 * @package Sandshark\DifmBundle\Playlist
 */
use Sandshark\DifmBundle\Collection\ChannelCollection;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * Class AbstractPlaylist
 * @package Sandshark\DifmBundle\Playlist
 */
class AbstractPlaylist
{
    /**
     * Collection of objects representing channels
     * @var ChannelCollection
     */
    protected $channels;

    /**
     * Private premium key
     * @var string
     */
    protected $listenKey = null;

    /**
     * Premium or free user
     * @var bool
     */
    protected $premium = false;

    /**
     * Audio quality setting
     * @var string
     */
    protected $quality = '_aac';

    /**
     * Site for filename
     * @var string
     */
    protected $site;

    /**
     * Class constructor
     * @param ChannelCollection $channels
     * @throws InvalidArgumentException
     */
    public function __construct(ChannelCollection $channels)
    {
        if (!count($channels)) {
            throw new InvalidArgumentException('There are no channels');
        }
        $this->channels = $channels;
    }

    /**
     * Set the listen key
     * @param string $listenKey
     * @return $this
     */
    public function setListenKey($listenKey)
    {
        if (!is_string($listenKey)) {
            throw new InvalidArgumentException(sprintf('Invalid listen key \'%s\'', $listenKey));
        }
        $this->listenKey = $listenKey;
        return $this;
    }

    /**
     * Returns a difm_<Y-m-d><_$listenKey>.$extension file name string
     * @return string
     */
    public function getFileName()
    {
        $className = get_class($this);
        preg_match('/\w*$/i', $className, $extension);
        $extension = strtolower($extension[0]);
        $key = empty($this->listenKey) ? '' : "_$this->listenKey";
        return sprintf('%s_%s%s.%s', date('Y-m-d'), $this->site, $key, $extension);
    }

    /**
     * Set premium toggle
     * @param boolean $premium
     * @return $this
     */
    public function setPremium($premium)
    {
        $this->premium = (bool)$premium;
        return $this;
    }

    /**
     * Set quality
     * @param string $quality
     * @return $this
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        return $this;
    }

    /**
     * Set site for filename
     * @param string $site
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = (string)$site;
        return $this;
    }
}
