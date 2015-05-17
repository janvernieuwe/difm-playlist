<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 14/05/15
 * Time: 23:06
 */

namespace Sandshark\DifmBundle\Tests\Api;


use Sandshark\DifmBundle\Api\ChannelHydrator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator;

class HydratorTest extends WebTestCase
{

    /**
     * @var \Sandshark\DifmBundle\Api\ChannelHydrator
     */
    private $hydrator;

    /**
     * @var \stdClass
     */
    private $validChannelObject;

    public function setUp()
    {
        $this->validChannelObject = (object)array(
            'id'       => 1,
            'name'     => 'Test Channel',
            'key'      => 'testchannel',
            'playlist' => 'http://example.com/test.pls'
        );
        /** @var Validator $validator */
        $validator = $this->get('validator');
        $this->hydrator = new ChannelHydrator($validator);
    }

    private function get($key)
    {
        return self::createClient()
            ->getContainer()
            ->get($key);
    }

    public function testValidParams()
    {
        $channelStd = clone $this->validChannelObject;
        $channel = $this->hydrator->hydrate($channelStd);
        $this->assertEquals($channelStd->id, $channel->getChannelId());
        $this->assertEquals($channelStd->key, $channel->getChannelKey());
        $this->assertEquals($channelStd->name, $channel->getChannelName());
        $this->assertEquals($channelStd->playlist, $channel->getChannelPlaylist());
        $this->assertEquals(null, $channel->getId());
    }

    public function testInvalidNameNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->name = null;
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidNameEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->name = '';
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidKeyNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->key = null;
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidKeyEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->key = '';
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidPlaylistNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->playlist = null;
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidPlaylistEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->playlist = '';
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidIdNull()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->id = null;
        $this->hydrator->hydrate($channel);
    }

    public function testInvalidIdString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $channel = clone $this->validChannelObject;
        $channel->id = '';
        $this->hydrator->hydrate($channel);
    }
}
