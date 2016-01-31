<?php

namespace DifmBundle\Controller;

use DifmBundle\Api\Channels;
use DifmBundle\Playlist\M3u;
use DifmBundle\Playlist\Pls;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('::base.html.twig');
    }

    public function downloadAction($station, $key, $_format)
    {
        if (!in_array($station, ['difm', 'rockradio', 'jazzradio', 'radiotunes'])) {
            throw new NotFoundHttpException(sprintf('Station %s not supported', $station));
        }
        /** @var Channels $channels */
        $channels = $this->get($station . '.channels');
        $playlist = $_format === 'm3u' ? new M3u($channels) : new Pls($channels);
        $playlist->setListenKey($key);
        return $playlist->render();
    }
}
