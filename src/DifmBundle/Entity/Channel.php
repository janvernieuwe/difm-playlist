<?php

namespace DifmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Channel.
 */
class Channel
{
    /**
     * ChannelProvider channel id.
     * @var int
     * @Assert\GreaterThan(0)
     * @Assert\Type("integer")
     */
    private $channelId;

    /**
     * ChannelProvider channel key.
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelKey;

    /**
     * ChannelProvider channel name.
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelName;

    /**
     * URI to channel .pls.
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelPlaylist;

    /**
     * Channel constructor.
     * @param $channelId
     * @param string $channelKey
     * @param $channelName
     * @param $channelPlaylist
     */
    public function __construct($channelId, $channelKey, $channelName, $channelPlaylist)
    {
        $this->channelId = $channelId;
        $this->channelKey = $channelKey;
        $this->channelName = $channelName;
        $this->channelPlaylist = $channelPlaylist;
    }

    //    private $bitRateMap = [
    //        'difm' => [
    //            'public' => [
    //                40 => 'public2',
    //                64 => 'public1',
    //                96 => 'public3',
    //            ],
    //            'premium' => [
    //                40 => '_low',
    //                64 => '_medium',
    //                128 => '',
    //                256 => '_high',
    //            ],
    //        ],
    //        'radiotunes' => [
    //            'public' => [],
    //            'premium' => [],
    //        ],
    //        'rockradio' => [
    //            'public' => [],
    //            'premium' => [],
    //        ],
    //    ];

    /**
     * Get channelId.
     * @return int
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Get channelName.
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * Get channelPlaylist.
     * @return string
     */
    public function getChannelPlaylist()
    {
        return $this->channelPlaylist;
    }

    /**
     * Get the url for streaming.
     * @param bool   $premium
     * @param string $key
     * @return string
     */
    public function getStreamUrl($premium, $key = ''/*, $bitRate = 96*/)
    {
        $premium = (bool) $premium;
        if ($premium && empty($key)) {
            throw new InvalidArgumentException('listenKey is required when premium');
        }
        $key = is_null($key) ? '' : '?'.$key;
        // Premium
        if ($premium) {
            //$quality = $this->bitRateMap[$this->s]
            $qualityMap = [
                'di.fm' => 'hi',
                'radiotunes.com' => 'hi',
                'jazzradio.com' => 'low',
                'rockradio.com' => 'low',
            ];
            $quality = $qualityMap[$this->getDomain()];

            return sprintf(
                'http://%s:80/%s_%s%s',
                $this->getHostName($premium),
                $this->getStreamKey($premium),
                $quality,
                $key
            );
        }
        $prefixMap = [
            'di.fm' => 'di',
            'radiotunes.com' => 'radiotunes',
            'jazzradio.com' => 'jr',
            'rockradio.com' => 'rr',
        ];
        $prefix = $prefixMap[$this->getDomain()];
        return sprintf(
            'http://%s:80/%s_%s_aacplus%s',
            $this->getHostName($premium),
            $prefix,
            $this->getStreamKey($premium),
            $key
        );
    }

    /**
     * Get hostname depending on the premium toggle.
     * @param bool $premium
     * @return string
     */
    private function getHostName($premium)
    {
        $domain = $this->getDomain();
        $sub = $premium ? 'prem' : 'pub';
        if ($domain === 'di.fm') {
            $id = $premium ? rand(1, 4) : rand(1, 8);
        } elseif ($domain === 'rockradio.com') {
            $id = $premium ? rand(1, 4) : 7;
        } else {
            $id = $premium ? rand(1, 4) : rand(1, 6);
        }

        return sprintf('%s%d.%s', $sub, $id, $domain);
    }

    /**
     * Get domain name from playlist.
     * @return string
     */
    public function getDomain()
    {
        $url = parse_url($this->channelPlaylist);

        return (string) str_replace('listen.', '', $url['host']);
    }

    /**
     * Get the stream key that keeps in mind the various exceptions.
     * @param bool $premium
     * @return string
     */
    public function getStreamKey($premium)
    {
        $host = $this->getDomain();
        $keyMap = [];
        if ($host === 'di.fm') {
            $keyMap = [
                'club' => $premium ? 'club' : 'clubsounds',
                'electro' => 'electrohouse',
                'classictechno' => $premium ? 'classicelectronica' : 'oldschoolelectronica',
            ];
        }
        if ($host === 'radiotunes.com') {
            $keyMap = [
                'ambient' => 'rtambient',
                'chillout' => 'rtchillout',
                'downtempolounge' => 'rtdowntempolounge',
                'eurodance' => 'rteurodance',
                'lounge' => 'rtlounge',
                'vocalchillout' => 'rtvocalchillout',
                'vocallounge' => 'rtvocallounge',
            ];
        }
        $key = $this->getChannelKey();
        if (array_key_exists($key, $keyMap)) {
            $key = $keyMap[$key];
        }
        return $key;
    }

    /**
     * Get channelKey.
     * @return string
     */
    public function getChannelKey()
    {
        return $this->channelKey;
    }
}
