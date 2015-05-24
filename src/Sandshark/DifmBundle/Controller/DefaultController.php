<?php

namespace Sandshark\DifmBundle\Controller;

use Sandshark\DifmBundle\Entity\PlaylistConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        return $this->render(
            '@SandsharkDifm/Default/index.html.twig',
            [
                'difm'       => $this->get('channel_difm'),
                'radiotunes' => $this->get('channel_radiotunes'),
                'jazzradio'  => $this->get('channel_jazzradio'),
                'rockradio'  => $this->get('channel_rockradio'),
                'errors'     => $this->get('session')
                    ->getFlashBag()
                    ->get('error', [])
            ]
        );
    }

    /**
     * Class constructor
     * @param string $listenKey
     * @param string $format
     * @param string $premium
     * @param string $site
     * @return Response
     */
    public function renderAction($listenKey, $format, $premium = 'public', $site = 'difm')
    {
        $playlistConfig = PlaylistConfiguration::create()
            ->setSite($site)
            ->setFormat($format)
            ->setPremium($premium)
            ->setListenKey($listenKey);
        $errors = $this->get('validator')
            ->validate($playlistConfig);
        if (count($errors)) {
            $session = $this->get('session');
            $session->getFlashBag()->add('error', (string) $errors);
            return new RedirectResponse($this->generateUrl('sandshark_difm_homepage'));
        }
        return $this->get('difm_provider.playlist')
            ->create($playlistConfig)
            ->render();
    }
}
