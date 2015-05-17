<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 17/05/15
 * Time: 19:35
 */

namespace Sandshark\DifmBundle\Api;


use Sandshark\DifmBundle\Collection\ChannelCollection;
use Sandshark\DifmBundle\Entity\Channel;

class CollisionResolver
{
    private $mainCollection;

    public function __construct(ChannelCollection $mainCollection)
    {
        $this->mainCollection = $mainCollection;
    }

    public function resolve(ChannelCollection $channelCollection, $prefix)
    {
        /** @var Channel $channel */
        foreach ($channelCollection as $channel) {
            $key = $channel->getChannelKey();
            if ($this->mainCollection->offsetExists($key)) {
                $newKey = $prefix . $key;
                $channel->setChannelKey($newKey);
                $channelCollection->offsetSet($key, $channel);
                //$channelCollection->offsetUnset($key);
            }
        }
        return $channelCollection;
    }
}
