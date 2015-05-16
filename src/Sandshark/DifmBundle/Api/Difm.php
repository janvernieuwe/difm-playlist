<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:54
 */

namespace Sandshark\DifmBundle\Api;


use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use Sandshark\DifmBundle\Collection\ChannelCollection;

/**
 * Class Difm
 * @package Sandshark\DifmBundle\Api
 */
class Difm
{
    /**
     * Base url for di.fm api
     * @see http://difm.eu/dox/#8
     * @type string
     */
    const BASE_URL = 'http://listen.di.fm';

    /**
     * Channels endpoint
     * @type string
     */
    const CHANNELS = 'streamlist';

    /**
     * Time in seconds to cache a request
     * @type int
     */
    const CACHE_LIFETIME = 3600;

    /**
     * @var Client
     */
    private $api;

    /**
     * Class constructor
     * Takes care of configuration
     * @param Client $api
     * @param FilesystemCache $cache
     */
    public function __construct(Client $api, FilesystemCache $cache)
    {
        $this->api = $api;
        $this->cache = $cache;
    }

    /**
     * Access api resources
     * Cached for CACHE_LIFETIME seconds
     * @param string $key
     * @return array<\stdClass>
     */
    public function get($key)
    {
        if ($this->cache->contains($key)) {
            return json_decode($this->cache->fetch($key));
        }
        $response = (string)$this->api
            ->get($key)
            ->getBody();
        $this->cache->save($key, $response, self::CACHE_LIFETIME);
        return json_decode($response);
    }

    /**
     * Get the collection of channels
     * @return \Sandshark\DifmBundle\Collection\ChannelCollection
     */
    public function getChannels()
    {
        $channels = $this->get(self::CHANNELS);
        //var_dump($channels);
        $channelCollection = new ChannelCollection();
        $channelHydrator = new ChannelHydrator();
        foreach ($channels as $data) {
            $channel = $channelHydrator->hydrate($data);
            $channelCollection->offsetSet($channel->getChannelKey(), $channel);
        }
        $channelCollection->ksort();
        return $channelCollection;
    }
}
