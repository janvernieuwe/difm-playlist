<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:54
 */

namespace Sandshark\DifmBundle\Api;


use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Client
 * @package Sandshark\DifmBundle\Api
 */
class Client
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
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $siteName;

    /**
     * @var int
     */
    protected $cacheLifetime;

    /**
     * @var Client
     */
    private $api;

    /**
     * Channel hydrator service
     * @var ChannelHydrator
     */
    private $hydrator;

    /**
     * Class constructor
     * Takes care of configuration
     * @param GuzzleClient $api
     * @param FilesystemCache $cache
     * @param ChannelHydrator $hydrator
     */
    public function __construct(GuzzleClient $api, FilesystemCache $cache, ChannelHydrator $hydrator)
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->hydrator = $hydrator;
    }

    /**
     * Get cache statistics
     * @return array|null
     */
    public function getChannelsCacheDate()
    {
        if ($this->cache->contains(self::CHANNELS . '_timestamp')) {
            return $this->cache->fetch(self::CHANNELS . '_timestamp');
        }
        return date('Y-m-d H:i:s');
    }

    /**
     * Get the collection of channels
     * @return \Sandshark\DifmBundle\Collection\ChannelCollection
     */
    public function getChannels()
    {
        $channels = $this->get(self::CHANNELS);
        $channelCollection = $this->hydrator
            ->hydrateCollection($channels);
        $channelCollection->ksort();
        return $channelCollection;
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
        $this->cache->save($key . '_timestamp', date('Y-m-d H:i:s'), self::CACHE_LIFETIME);
        return json_decode($response);
    }
}
