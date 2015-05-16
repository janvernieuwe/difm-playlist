<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 15/05/15
 * Time: 3:13
 */

namespace Sandshark\DifmBundle\Playlist;


use Sandshark\DifmBundle\Collection\ChannelCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PlaylistFactory
 * @package Sandshark\DifmBundle\Playlist
 */
class PlaylistFactory
{
    /**
     * @param string $format
     * @param ChannelCollection $channels
     * @return M3u|Pls
     */
    public static function create($format, ChannelCollection $channels)
    {
        switch ($format) {
            case 'pls': $playlist = new Pls($channels);
                break;
            case 'm3u': $playlist = new M3u($channels);
                break;
            default:
                throw new NotFoundHttpException(sprintf('Invalid format %s', $format));
        }
        return $playlist;
    }
}
