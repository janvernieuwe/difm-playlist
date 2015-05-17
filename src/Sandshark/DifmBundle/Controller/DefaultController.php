<?php

namespace Sandshark\DifmBundle\Controller;


use Sandshark\DifmBundle\Api\Difm;
use Sandshark\DifmBundle\Playlist\PlaylistFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Sandshark\DifmBundle\Controller
 */
class DefaultController extends Controller
{

    public function indexAction()
    {
        $difm = $this->get('sandshark_difm.api');
        $channels = $difm->getChannels();
        $cacheDate = $difm->getChannelsCacheDate();
        return $this->render(
            '@SandsharkDifm/Default/index.html.twig',
            array(
                'channelCount' => $channels->count(),
                'cacheDate'    => $cacheDate,
                'nextUpdate'   => date('Y-m-d H:i:s', strtotime($cacheDate) + Difm::CACHE_LIFETIME)
            )
        );
    }

    /**
     * Class constructor
     * @param Request $request
     * @param string $key
     * @param string $premium
     * @return Response
     */
    public function renderAction(Request $request, $key, $premium = 'public')
    {
        $premium = $premium === 'premium';
        $key = preg_replace('/[^\da-z]/', '', $key);
        $key = $key === 'difm' ? '' : $key;
        $format = $request->get('_format');
        $channels = $this->get('sandshark_difm.api')
            ->getChannels();
        $playlist = PlaylistFactory::create($format, $channels)
            ->setListenKey($key)
            ->setPremium($premium);
        return new Response(
            $playlist->render(),
            200,
            array(
                'content-type'        => $playlist->getContentType(),
                'content-disposition' => 'attachment; filename=' . $playlist->getFileName()
            )
        );
    }
}
