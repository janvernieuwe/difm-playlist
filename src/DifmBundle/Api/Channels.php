<?php

namespace DifmBundle\Api;

use DifmBundle\Entity\Channel;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Channels
{
    /**
     * @const string
     */
    const CHANNELS = 'streamlist';

    /**
     * @var Client
     */
    private $client;

    /**
     * Channels constructor.
     * @param Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Loads channels response
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function loadChannels()
    {
        $request = new Request('GET', self::CHANNELS);
        $response = $this->client->send($request);
        $channels = json_decode($response->getBody());
        array_walk(
            $channels,
            function (&$channel) {
                $channel = new Channel(
                    $channel->id,
                    $channel->key,
                    $channel->name,
                    $channel->playlist
                );
            }
        );
        return $channels;
    }
}
