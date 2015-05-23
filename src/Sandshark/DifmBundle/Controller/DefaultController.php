<?php

namespace Sandshark\DifmBundle\Controller;

use Psr\Log\InvalidArgumentException;
use Sandshark\DifmBundle\Api\CollisionResolver;
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
        $channels = $this->getChannels($site, $premium);
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

    /**
     * Get the channels fror difm or radiotunes
     * @param string $site 'difm'|'radiontunes'
     * @param boolean $premium
     * @return \Sandshark\DifmBundle\Collection\ChannelCollection
     */
    private function getChannels($site, $premium)
    {
        switch ($site) {
            case 'difm':
                $channels = $difmChannels = $this->get('channel_difm')->getChannels();
                $channels['club']->setChannelKey('clubsounds');
                $channels['electro']->setChannelKey('electrohouse');
                $channels['classictechno']->setChannelKey($premium ? 'classicelectronica' : 'oldschoolelectronica');
                break;
            case 'jazzradio':
                $channels = $this->get('channel_jazzradio')->getChannels();
                break;
            case 'rockradio':
                $channels = $this->get('channel_rockradio')->getChannels();
                break;
            case 'radiotunes':
                $difmChannels = $this->get('channel_difm')->getChannels();
                $channels = $this->get('channel_radiotunes')->getChannels();
                $collisionResolver = new CollisionResolver($difmChannels);
                $channels = $collisionResolver->resolve($channels, 'rt');
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid site \'%s\'', $site));
        }
        return $channels;
    }
}
