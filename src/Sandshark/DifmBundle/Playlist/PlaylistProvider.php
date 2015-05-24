<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 15/05/15
 * Time: 3:13
 */

namespace Sandshark\DifmBundle\Playlist;

use Sandshark\DifmBundle\Entity\PlaylistConfiguration;
use Sandshark\DifmBundle\Exception\BadInterfaceException;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class PlaylistProvider
 * @package Sandshark\DifmBundle\Playlist
 */
class PlaylistProvider
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param PlaylistConfiguration $config
     * @return M3u|Pls
     * @throws BadInterfaceException
     */
    public function create(PlaylistConfiguration $config)
    {
        $playlist = null;
        $channels = $this->container->get(sprintf('channel_%s', $config->getSite()))
            ->getChannels($config);
        if ($config->getFormat() === 'pls') {
            $playlist = new Pls($channels);
        }
        if ($config->getFormat() === 'm3u') {
            $playlist = new M3u($channels);
        }
        if (!$playlist instanceof PlaylistInterface) {
            throw new BadInterfaceException('PlaylistInterface', $playlist);
        }
        $playlist->setListenKey($config->getListenKey())
            ->setPremium($config->getPremium())
            ->setSite($config->getSite());
        return $playlist;
    }
}
