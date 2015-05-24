<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests\Collection;

use Sandshark\DifmBundle\Collection\ChannelCollection;
use Sandshark\DifmBundle\Entity\Channel;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class ChannelCollectionTest
 * @package Sandshark\DifmBundle\Tests\Collection
 */
class ChannelCollectionTest extends TestCase
{

    private $channelCollection;

    public function setUp()
    {
        $this->channelCollection = new ChannelCollection();
    }

    public function testAppendNull()
    {
        $this->setExpectedException('Sandshark\DifmBundle\Exception\BadInstanceException');
        $this->channelCollection[] = null;
    }

    public function testSetNull()
    {
        $this->setExpectedException('Sandshark\DifmBundle\Exception\BadInstanceException');
        $this->channelCollection['test'] = null;
    }

    public function testAppendObject()
    {
        $this->setExpectedException('Sandshark\DifmBundle\Exception\BadInstanceException');
        $this->channelCollection[] = new \stdClass();
    }

    public function testSetObject()
    {
        $this->setExpectedException('Sandshark\DifmBundle\Exception\BadInstanceException');
        $this->channelCollection['test'] = new \stdClass();
    }

    public function testValid()
    {
        $channel = new Channel();
        $this->channelCollection[] = $channel;
        $this->channelCollection['test'] = $channel;
    }
}
