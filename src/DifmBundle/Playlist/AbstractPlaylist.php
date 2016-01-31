<?php

namespace DifmBundle\Playlist;

use DifmBundle\Api\Channels;
use DifmBundle\Entity\Channel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

abstract class AbstractPlaylist implements PlaylistInterface
{
    /**
     * Collection of objects representing channels.
     * @var Channel[]
     */
    protected $channels;

    /**
     * Private premium key.
     * @var string
     */
    protected $listenKey = null;

    /**
     * Premium or free user.
     * @var bool
     */
    protected $premium = true;

    /**
     * Audio quality setting.
     * @var string
     */
    protected $quality = '_aac';

    /**
     * Site for filename.
     * @var string
     */
    protected $site;

    /**
     * Class constructor.
     * @param Channels $channels
     * @throws InvalidArgumentException
     */
    public function __construct(Channels $channels)
    {
        if (!count($channels->loadChannels())) {
            throw new InvalidArgumentException('There are no channels');
        }
        $this->channels = $channels->loadChannels();
    }

    /**
     * Set the listen key.
     * @param string $listenKey
     * @return $this
     */
    public function setListenKey($listenKey)
    {
        if (!is_string($listenKey)) {
            throw new InvalidArgumentException(sprintf('Invalid listen key \'%s\'', $listenKey));
        }
        $this->listenKey = $listenKey;
        return $this;
    }

    /**
     * Set premium toggle.
     * @param bool $premium
     * @return $this
     */
    public function setPremium($premium)
    {
        $this->premium = (bool)$premium;
        return $this;
    }

    /**
     * Set quality.
     * @param string $quality
     * @return $this
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        return $this;
    }

    /**
     * Set site for filename.
     * @param string $site
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = (string)$site;
        return $this;
    }

    /**
     * @param null $data
     * @return Response
     */
    public function render($data = null)
    {
        return new Response(
            $data,
            200,
            [
                'content-type'        => $this->getContentType(),
                'content-disposition' => 'attachment; filename=' . $this->getFileName(),
            ]
        );
    }

    /**
     * Returns a difm_<Y-m-d><_$listenKey>.$extension file name string.
     * @return string
     */
    public function getFileName()
    {
        $className = get_class($this);
        preg_match('/\w*$/i', $className, $extension);
        $extension = strtolower($extension[0]);
        $key = empty($this->listenKey) ? '' : "_$this->listenKey";
        return sprintf('%s_%s%s.%s', date('Y-m-d'), $this->site, $key, $extension);
    }
}
