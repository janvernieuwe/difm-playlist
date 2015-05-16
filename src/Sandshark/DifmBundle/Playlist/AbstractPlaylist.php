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
    protected $listenKey;

    /**
     * Class constructor
     * @param ChannelCollection $channels
     * @param string $listenKey
     * @throws InvalidArgumentException
     */
    public function __construct(ChannelCollection $channels, $listenKey = '')
    {
        if (!is_string($listenKey)) {
            throw new InvalidArgumentException('listenKey should be a string');
        }
        if (!count($channels)) {
            throw new InvalidArgumentException('There are no channels');
        }
        $this->channels = $channels;
        $this->listenKey = $listenKey;
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
}
