<?php

namespace Sandshark\DifmBundle\Controller;

use Psr\Log\InvalidArgumentException;
use Sandshark\DifmBundle\Api\Client;
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
        $difm = $this->get('sandshark_difm.api');
        $diChannels = $difm->getChannels();
        $diCacheDate = $difm->getChannelsCacheDate();

        $radioTunes = $this->get('sandshark_radiotunes.api');
        $rtChannels = $radioTunes->getChannels();
        $rtCacheDate = $radioTunes->getChannelsCacheDate();

        return $this->render(
            '@SandsharkDifm/Default/index.html.twig',
            array(
                'diChannelCount' => $diChannels->count(),
                'diCacheDate'    => $diCacheDate,
                'diNextUpdate'   => date('Y-m-d H:i:s', strtotime($diCacheDate) + Client::CACHE_LIFETIME),
                'rtChannelCount' => $rtChannels->count(),
                'rtCacheDate'    => $rtCacheDate,
                'rtNextUpdate'   => date('Y-m-d H:i:s', strtotime($rtCacheDate) + Client::CACHE_LIFETIME)
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
                $channels = $difmChannels = $this->get('sandshark_difm.api')->getChannels();
                $channels['club']->setChannelKey('clubsounds');
                $channels['electro']->setChannelKey('electrohouse');
                $channels['classictechno']->setChannelKey($premium ? 'classicelectronica' : 'oldschoolelectronica');
                break;
            case 'jazzradio':
                $channels = $this->get('sandshark_jazzradio.api')->getChannels();
                break;
            case 'rockradio':
                $channels = $this->get('sandshark_rockradio.api')->getChannels();
                break;
            case 'radiotunes':
                $difmChannels = $this->get('sandshark_difm.api')->getChannels();
                $channels = $this->get('sandshark_radiotunes.api')->getChannels();
                $collisionResolver = new CollisionResolver($difmChannels);
                $channels = $collisionResolver->resolve($channels, 'rt');
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid site \'%s\'', $site));
        }
        return $channels;
    }
}
