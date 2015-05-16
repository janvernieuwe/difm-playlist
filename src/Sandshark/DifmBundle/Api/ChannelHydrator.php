<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 21:49
 */

namespace Sandshark\DifmBundle\Api;


use Sandshark\DifmBundle\Entity\Channel;

/**
 * Class ChannelHydrator
 * @package Sandshark\DifmBundle\Api
 */
class ChannelHydrator
{
    /**
     * Hydrates a channel object
     * @param \stdClass $channel
     * @return \Sandshark\DifmBundle\Entity\Channel
     */
    public function hydrate($channel)
    {
        $entity = new Channel();
        $entity->setChannelId($channel->id);
        $entity->setChannelKey($channel->key);
        $entity->setChannelName($channel->name);
        $entity->setChannelPlaylist($channel->playlist);
        return $entity;
    }
}
