<?php

namespace Sandshark\DifmBundle\Controller;

use Sandshark\DifmBundle\Entity\PlaylistConfiguration;
use Sandshark\DifmBundle\Playlist\PlaylistProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        return $this->render(
            '@SandsharkDifm/Default/index.html.twig',
            array(
                'difm'       => $this->get('channel_difm'),
                'radiotunes' => $this->get('channel_radiotunes'),
                'jazzradio'  => $this->get('channel_jazzradio'),
                'rockradio'  => $this->get('channel_rockradio'),
                'errors'     => $this->get('session')
                    ->getFlashBag()
                    ->get('error', array())
            )
        );
    }

    /**
     * Class constructor
     * @param $listenKey
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
            $this->setFlashMessage('error', (string) $errors);
            return new RedirectResponse($this->generateUrl('sandshark_difm_homepage'));
        }
        return $this->get('difm_provider.playlist')
            ->create($playlistConfig)
            ->render();
    }

    /**
     * Gets the session and sets the message in the FlashBag
     * @param string $type
     * @param string $msg
     */
    private function setFlashMessage($type, $msg)
    {
        $session = $this->get('session');
        $session->getFlashBag()->add($type, $msg);
    }
}
