<?php

namespace Sandshark\DifmBundle\Controller;

use Sandshark\DifmBundle\Playlist\PlaylistFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class DefaultController
 * @package Sandshark\DifmBundle\Controller
 */
class DefaultController extends Controller
{

    /**
     * Display index page
     * @return Response
     */
    public function indexAction()
    {
        $digitallyImported = $this->get('channel_difm');
        $radioTunes = $this->get('channel_radiotunes');

        return $this->render(
            '@SandsharkDifm/Default/index.html.twig',
            array(
                'diChannelCount' => $digitallyImported->getChannelCount(),
                'diCacheDate'    => $digitallyImported->cachedAt(),
                'diNextUpdate'   => $digitallyImported->expiresAt(),
                'rtChannelCount' => $radioTunes->getChannelCount(),
                'rtCacheDate'    => $radioTunes->cachedAt(),
                'rtNextUpdate'   => $radioTunes->expiresAt()
            )
        );
    }

    /**
     * Class constructor
     * @param Request $request
     * @param string $key
     * @param string $premium
     * @param string $site
     * @return Response
     */
    public function renderAction(Request $request, $key, $premium = 'public', $site = 'difm')
    {
        $premium = $premium === 'premium';
        $key = $key === 'playlist' ? '' : $key;
        $key = preg_replace('/[^\da-z]/', '', $key);
        $format = $request->get('_format');
        $channels = null;
        if ($site === 'difm') {
            $channels = $this->get('channel_difm')
                ->getChannels();
        }
        if ($site === 'radiotunes') {
            $channels = $this->get('channel_radiotunes')
                ->getChannels();
        }
        if (is_null($channels)) {
            throw new ResourceNotFoundException(sprintf('Site %s is not supported', $site));
        }
        $playlist = PlaylistFactory::create($format, $channels)
            ->setListenKey($key)
            ->setPremium($premium)
            ->setSite($site);
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
