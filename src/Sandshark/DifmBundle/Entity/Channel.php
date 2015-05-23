<?php

namespace Sandshark\DifmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Channel
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sandshark\DifmBundle\Entity\ChannelRepository")
 */
class Channel
{
    /**
     * @var integer
     * @ORM\Column(name="id",    type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * ChannelProvider channel id
     * @var integer
     * @ORM\Column(name="channel_id",    type="integer")
     * @Assert\GreaterThan(0)
     * @Assert\Type("integer")
     */
    private $channelId;

    /**
     * ChannelProvider channel key
     * @var string
     * @ORM\Column(name="channel_key",    type="string",    length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelKey;

    /**
     * ChannelProvider channel name
     * @var string
     * @ORM\Column(name="channel_name",    type="string",    length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelName;

    /**
     * URI to channel .pls
     * @var string
     * @ORM\Column(name="channel_playlist",    type="string",    length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelPlaylist;


    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get channelId
     * @return integer
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set channelId
     * @param integer $channelId
     * @return Channel
     */
    public function setChannelId($channelId)
    {
        $this->channelId = (int)$channelId;
        return $this;
    }

    /**
     * Get channelName
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * Set channelName
     * @param string $channelName
     * @return Channel
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
        return $this;
    }

    /**
     * Get channelPlaylist
     * @return string
     */
    public function getChannelPlaylist()
    {
        return $this->channelPlaylist;
    }

    /**
     * Set channelPlaylist
     * @param string $channelPlaylist
     * @return Channel
     */
    public function setChannelPlaylist($channelPlaylist)
    {
        $this->channelPlaylist = $channelPlaylist;
        return $this;
    }

    /**
     * Get the url for streaming
     * @param bool $premium
     * @param string $key
     * @return string
     */
    public function getStreamUrl($premium, $key = '')
    {
        $premium = (bool)$premium;
        if ($premium && empty($key)) {
            throw new InvalidArgumentException('listenKey is required when premium');
        }
        $key = is_null($key) ? '' : '?' . $key;
        // Premium
        if ($premium) {
            return sprintf(
                'http://%s:80/%s_%s%s',
                $this->getHostName($premium),
                $this->getStreamKey($premium),
                'hi',
                $key
            );
        }
        $prefix = explode('.', $this->getDomain());
        $prefix = array_shift($prefix);
        return sprintf(
            'http://%s/%s_%s_%s%s',
            $this->getHostName($premium),
            $prefix,
            $this->getStreamKey($premium),
            'aac',
            $key
        );
    }

    /**
     * Get hostname depending on the premium toggle
     * @param bool $premium
     * @return string
     */
    private function getHostName($premium)
    {
        $domain = $this->getDomain();
        $sub = $premium ? 'prem' : 'pub';
        if ($domain === 'di.fm') {
            $id = $premium ? rand(1, 4) : rand(1, 8);
        } else {
            $id = $premium ? rand(1, 4) : rand(1, 6);
        }
        return sprintf('%s%d.%s', $sub, $id, $domain);
    }

    /**
     * Get domain name from playlist
     * @return string
     */
    public function getDomain()
    {
        $url = parse_url($this->channelPlaylist);
        return (string)str_replace('listen.', '', $url['host']);
    }

    /**
     * Get the stream key that keeps in mind the various exceptions
     * @param boolean $premium
     * @return string
     */
    public function getStreamKey($premium)
    {
        $host = $this->getDomain();
        $keyMap = array();
        if ($host === 'di.fm') {
            $keyMap = array(
                'club'          => 'clubsounds',
                'electro'       => 'electrohouse',
                'classictechno' => $premium ? 'classicelectronica' : 'oldschoolelectronica'
            );
        }
        if ($host === 'radiotunes.com') {
            $keyMap = array(
                'ambient'         => 'rtambient',
                'chillout'        => 'rtchillout',
                'downtempolounge' => 'rtdowntempolounge',
                'eurodance'       => 'rteurodance',
                'lounge'          => 'rtlounge',
                'vocalchillout'   => 'rtvocalchillout',
                'vocallounge'     => 'rtvocallounge'
            );
        }
        $key = $this->getChannelKey();
        if (array_key_exists($key, $keyMap)) {
            $key = $keyMap[$key];
        }
        return $key;
    }

    /**
     * Get channelKey
     * @return string
     */
    public function getChannelKey()
    {
        return $this->channelKey;
    }

    /**
     * Set channelKey
     * @param string $channelKey
     * @return Channel
     */
    public function setChannelKey($channelKey)
    {
        $this->channelKey = $channelKey;
        return $this;
    }
}
