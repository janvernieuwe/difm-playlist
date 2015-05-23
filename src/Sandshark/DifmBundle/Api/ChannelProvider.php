<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 11/05/15
 * Time: 22:54
 */

namespace Sandshark\DifmBundle\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\FutureResponse;
use Sandshark\DifmBundle\Entity\Channel;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ChannelProvider
 * @package Sandshark\DifmBundle\Api
 */
class ChannelProvider
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
     * @var ChannelProvider
     */
    private $api;

    /**
     * Channel hydrator service
     * @var ChannelHydrator
     */
    private $hydrator;

    /**
     * Free listen key
     * Should be loaded from configuration or input
     * @var null|string
     */
    private $publicKey;

    /**
     * Premium listen key
     * Should be loaded from configuration or input
     * @var null|string
     */
    private $premiumKey;

    /**
     * Class constructor
     * @param Container $container
     * @param GuzzleClient $api
     * @param string $publicKey
     * @param string $premiumKey
     */
    public function __construct(Container $container, GuzzleClient $api, $publicKey = null, $premiumKey = null)
    {
        $this->api = $api;
        $this->cache = $container->get('cache_file');
        $this->hydrator = $container->get('hydrator_channel');
        $this->publicKey = $publicKey;
        $this->premiumKey = $premiumKey;
    }

    /**
     * Get cache statistics
     * @return array|null
     */
    public function cachedAt()
    {
        $timestampKey = $this->getCacheKey(self::CHANNELS . '_timestamp');
        if ($this->cache->contains($timestampKey)) {
            return $this->cache->fetch($timestampKey);
        }
        return date('Y-m-d H:i:s');
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

    /**
     * Get cache statistics
     * @return array|null
     */
    public function expiresAt()
    {
        $timestampKey = $this->getCacheKey(self::CHANNELS . '_timestamp');
        if ($this->cache->contains($timestampKey)) {
            $timestamp = $this->cache->fetch($timestampKey);
        } else {
            $timestamp = date('Y-m-d H:i:s');
        }
        $timestamp = strtotime($timestamp);
        return date('Y-m-d H:i:s', $timestamp + self::CACHE_LIFETIME);
    }

    /**
     * @param Channel $channel
     * @param bool $premium
     */
    public function getPlaylist(Channel $channel, $premium = false)
    {
    }

    /**
     * Get the amount of channels
     * @return int
     */
    public function getChannelCount()
    {
        return count($this->getChannels());
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
        /** @var FutureResponse $response */
        $response = $this->api
            ->get($key);
        $response = (string) $response->getBody();
        $this->cache->save($responseKey, $response, self::CACHE_LIFETIME);
        $this->cache->save($timestampKey, date('Y-m-d H:i:s'), self::CACHE_LIFETIME);
        return json_decode($response);
    }
}
