<?php

namespace Sandshark\DifmBundle\Controller;


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
        $format = $request->get('_format');
        $channels = $this->get('sandshark_difm.api')->getChannels();
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
