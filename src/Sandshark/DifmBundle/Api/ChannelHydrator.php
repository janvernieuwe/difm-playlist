<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 21:49
 */

namespace Sandshark\DifmBundle\Api;

use Sandshark\DifmBundle\Collection\ChannelCollection;
use Sandshark\DifmBundle\Entity\Channel;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator;

/**
 * Class ChannelHydrator
 * @package Sandshark\DifmBundle\Api
 */
class ChannelHydrator
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * Entity validator
     * @param \Symfony\Component\Validator\Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Hydrate an entiry channelCollection
     * @param array <\stdClass> $arrStd
     * @return ChannelCollection
     */
    public function hydrateCollection($arrStd)
    {
        $channelCollection = new ChannelCollection();
        foreach ($arrStd as $data) {
            $channel = $this->hydrate($data);
            $channelCollection->offsetSet($channel->getChannelKey(), $channel);
        }
        return $channelCollection;
    }

    /**
     * Hydrates a channel object
     * @param \stdClass $channel
     * @return Channel
     * @throws InvalidArgumentException
     */
    public function hydrate($channel)
    {
        $entity = new Channel();
        $entity->setChannelId($channel->id);
        $entity->setChannelKey($channel->key);
        $entity->setChannelName($channel->name);
        $entity->setChannelPlaylist($channel->playlist);
        $errors = $this->validator
            ->validate($entity);
        if (count($errors)) {
            throw new InvalidArgumentException((string) $errors);
        }
        return $entity;
    }
}
