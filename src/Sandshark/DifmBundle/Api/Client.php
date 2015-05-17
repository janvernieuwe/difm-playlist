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
        $timestampKey = $this->getCacheKey(self::CHANNELS . '_timestamp');
        if ($this->cache->contains($timestampKey)) {
            return $this->cache->fetch($timestampKey);
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
        $responseKey = $this->getCacheKey($key);
        $timestampKey = $this->getCacheKey($key . '_timestamp');
        if ($this->cache->contains($responseKey)) {
            return json_decode($this->cache->fetch($responseKey));
        }
        $response = (string)$this->api
            ->get($key)
            ->getBody();
        $this->cache->save($responseKey, $response, self::CACHE_LIFETIME);
        $this->cache->save($timestampKey, date('Y-m-d H:i:s'), self::CACHE_LIFETIME);
        return json_decode($response);
    }

    /**
     * Get cache key from a normal key, prepends the request base_url
     * @param $key
     * @return string
     */
    private function getCacheKey($key)
    {
        $url = parse_url($this->api->getBaseUrl());
        return sprintf(
            '%s_%s',
            $url['host'],
            $key
        );
    }
}
