<?php

namespace Sandshark\DifmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

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
     * Difm channel id
     * @var integer
     * @ORM\Column(name="channel_id", type="integer")
     */
    private $channelId;

    /**
     * Difm channel key
     * @var string
     * @ORM\Column(name="channel_key", type="string", length=255)
     */
    private $channelKey;

    /**
     * Difm channel name
     * @var string
     * @ORM\Column(name="channel_name", type="string", length=255)
     */
    private $channelName;

    /**
     * URI to channel .pls
     * @var string
     * @ORM\Column(name="channel_playlist", type="string", length=255)
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
     * Set channelId
     *
     * @param integer $channelId
     * @return Channel
     */
    public function setChannelId($channelId)
    {
        if (!is_numeric($channelId)) {
            throw new InvalidArgumentException(sprintf('Channel id %s is invalid', $channelId));
        }
        $this->channelId = (int)$channelId;
        return $this;
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
     * Set channelKey
     *
     * @param string $channelKey
     * @return Channel
     */
    public function setChannelKey($channelKey)
    {
        if (!is_string($channelKey) || empty($channelKey)) {
            throw new InvalidArgumentException(sprintf('Channel key \'%s\' is invalid', $channelKey));
        }
        $this->channelKey = $channelKey;
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
     * Set channelName
     *
     * @param string $channelName
     * @return Channel
     */
    public function setChannelName($channelName)
    {
        if (!is_string($channelName) || empty($channelName)) {
            throw new InvalidArgumentException(sprintf('Channel name \'%s\' is invalid', $channelName));
        }
        $this->channelName = $channelName;
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
     * Set channelPlaylist
     *
     * @param string $channelPlaylist
     * @return Channel
     */
    public function setChannelPlaylist($channelPlaylist)
    {
        if (!is_string($channelPlaylist) || empty($channelPlaylist)) {
            throw new InvalidArgumentException(sprintf('Channel playlist \'%s\' is invalid', $channelPlaylist));
        }
        $this->channelPlaylist = $channelPlaylist;
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
}
