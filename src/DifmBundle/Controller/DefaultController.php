<?php

namespace DifmBundle\Controller;

use DifmBundle\Api\Channels;
use DifmBundle\Playlist\M3u;
use DifmBundle\Playlist\Pls;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package DifmBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     */
    public function indexAction()
    {
        return $this->render(
            'DifmBundle:Default:index.html.twig',
            [
                'difm'           => $this->get('difm.channels')->loadChannels(),
                'rockradio'      => $this->get('rockradio.channels')->loadChannels(),
                'jazzradio'      => $this->get('jazzradio.channels')->loadChannels(),
                'radiotunes'     => $this->get('radiotunes.channels')->loadChannels(),
                'classicalradio' => $this->get('radiotunes.channels')->loadChannels(),
            ]
        );
    }

    /**
     * @param string $station
     * @param int $quality
     * @param string $key
     * @param string $_format
     * @return string
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Validator\Exception\InvalidArgumentException
     */
    public function downloadAction($station, $quality, $key, $_format)
    {
        if ((int)$quality === 320 && $station !== 'difm') {
            throw new NotFoundHttpException('320 kbps quality is only available for difm');
        }
        /** @var Channels $channels */
        $channels = $this->get($station.'.channels');
        $playlist = $_format === 'm3u' ? new M3u($channels) : new Pls($channels);
        $playlist->setListenKey($key);
        $playlist->setQuality($quality);

        return $playlist->render();
    }
}
