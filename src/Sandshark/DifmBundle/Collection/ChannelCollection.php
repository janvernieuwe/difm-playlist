<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 21:57
 */

namespace Sandshark\DifmBundle\Collection;

use Sandshark\DifmBundle\Entity\Channel;
use Sandshark\DifmBundle\Exception\BadInstanceException;

/**
 * Class ChannelCollection
 * @package Sandshark\DifmBundle\Collection
 */
class ChannelCollection extends \ArrayObject
{

    /**
     * Set a Channel by key
     * @param mixed $index
     * @param Channel $newVal
     * @throws BadInstanceException
     */
    public function offsetSet($index, $newVal)
    {
        if (!$newVal instanceof Channel) {
            throw new BadInstanceException('Channel', $newVal);
        }
        parent::offsetSet($index, $newVal);
    }

    /**
     * Append a Channel
     * @param Channel $value
     * @throws BadInstanceException
     */
    public function append($value)
    {
        if (!$value instanceof Channel) {
            throw new BadInstanceException('Channel', $value);
        }
        parent::__construct($value);
    }

    /**
     * @param mixed $index
     * @return Channel
     */
    public function offsetGet($index)
    {
        parent::offsetGet($index);
    }

    /**
     * @return array<Sandshark\DifmBundle\Entity\Channel>
     */
    public function getArrayCopy()
    {
        return parent::getArrayCopy();
    }

    /**
     * @param array $input
     * @return array
     */
    public function exchangeArray($input)
    {
        $this->validate($input);
        parent::exchangeArray($input);
        return $input;
    }

    /**
     * @param mixed $input
     * @return array<\Sandshark\DifmBundle\Entity\Channel>
     * @throws BadInstanceException
     */
    private function validate($input)
    {
        if (is_array($input)) {
            foreach ($input as $channel) {
                if (!$channel instanceof Channel) {
                    throw new BadInstanceException('Channel', $channel);
                }
            }
        }
        return $input;
    }
}
