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
use Sandshark\DifmBundle\Entity\Channel;
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
        if (!is_string($listenKey) || empty($listenKey)) {
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
        return sprintf('difm_%s%s.%s', date('Y-m-d'), $key, $extension);
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

    public function getStreamUrl(Channel $channel)
    {
        if ($this->premium) {
            return $this->getPremiumStreamUrl($channel);
        } else {
            return $this->getPublicStreamUrl($channel);
        }
    }

    /**
     * @param Channel $channel
     * @return string
     */
    public function getPremiumStreamUrl(Channel $channel)
    {
        $key = is_null($this->listenKey) ? '' : '?' . $this->listenKey;
        return sprintf(
            'http://%s:80/%s_%s%s',
            $this->getHostName($this->premium),
            $channel->getChannelKey(),
            'hi',
            $key
        );
    }

    /**
     * Get hostname depending on the premium toggle
     * @param bool $premium
     * @return string
     */
    protected function getHostName($premium)
    {
        $subDomain = $premium ? 'prem' : 'pub';
        // Public servers seem to range from 1-8, premium from 1-4
        $id = $premium ? rand(1, 4) : rand(1, 8);
        return sprintf('%s%d.di.fm', $subDomain, $id);
    }

    /**
     * @param Channel $channel
     * @return string
     */
    public function getPublicStreamUrl(Channel $channel)
    {
        $channelKey = preg_replace('/[^\d\w]/', '', $channel->getChannelName());
        $channelKey = strtolower($channelKey);
        $listenKey = is_null($this->listenKey) ? '' : '?' . $this->listenKey;
        return sprintf(
            'http://%s/di_%s_%s%s',
            $this->getHostName($this->premium),
            $channelKey,
            'aac',
            $listenKey
        );
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
}
