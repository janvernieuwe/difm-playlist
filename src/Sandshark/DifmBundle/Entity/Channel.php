<?php

namespace Sandshark\DifmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Channel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sandshark\DifmBundle\Entity\ChannelRepository")
 */
class Channel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * ChannelProvider channel id
     * @var integer
     * @ORM\Column(name="channel_id", type="integer")
     * @Assert\GreaterThan(0)
     * @Assert\Type("integer")
     */
    private $channelId;

    /**
     * ChannelProvider channel key
     * @var string
     * @ORM\Column(name="channel_key", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelKey;

    /**
     * ChannelProvider channel name
     * @var string
     * @ORM\Column(name="channel_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelName;

    /**
     * URI to channel .pls
     * @var string
     * @ORM\Column(name="channel_playlist", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $channelPlaylist;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get channelId
     *
     * @return integer
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set channelId
     *
     * @param integer $channelId
     * @return Channel
     */
    public function setChannelId($channelId)
    {
        $this->channelId = (int)$channelId;
        return $this;
    }

    /**
     * Get channelKey
     *
     * @return string
     */
    public function getChannelKey()
    {
        return $this->channelKey;
    }

    /**
     * Set channelKey
     *
     * @param string $channelKey
     * @return Channel
     */
    public function setChannelKey($channelKey)
    {
        $this->channelKey = $channelKey;
        return $this;
    }

    /**
     * Get channelName
     *
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * Set channelName
     *
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
     *
     * @return string
     */
    public function getChannelPlaylist()
    {
        return $this->channelPlaylist;
    }

    /**
     * Set channelPlaylist
     *
     * @param string $channelPlaylist
     * @return Channel
     */
    public function setChannelPlaylist($channelPlaylist)
    {
        $this->channelPlaylist = $channelPlaylist;
        return $this;
    }

    /**
     * Get domain name from playlist
     * @return string
     */
    public function getDomain()
    {
        $url = parse_url($this->channelPlaylist);
        return (string) str_replace('listen.', '', $url['host']);
    }
}
