<?php

namespace DifmBundle\Controller;

use DifmBundle\Api\Channels;
use DifmBundle\Playlist\M3u;
use DifmBundle\Playlist\Pls;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('::base.html.twig');
    }

    public function downloadAction($station, $key, $_format)
    {
        /** @var Channels $channels */
        $channels = $this->get($station . '.channels');
        $playlist = $_format === 'm3u' ? new M3u($channels) : new Pls($channels);
        $playlist->setListenKey($key);
        return $playlist->render();
    }
}
