<?php

namespace Sandshark\DifmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * PlaylistConfiguration
 * @ORM\Entity()
 * @Assert\Callback(methods={"isPremiumKeyValid"})
 */
class PlaylistConfiguration
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotNull(message="Site is required")
     * @Assert\Choice(
     *     choices = { "difm", "radiotunes", "jazzradio", "rockradio"},
     *     message = "Invalid site."
     * )
     * @ORM\Column(name="site", type="string", length=255)
     */
    private $site = 'difm';

    /**
     * @var string
     * @Assert\NotNull(message="Format is required")
     * @Assert\Choice(
     *     choices = { "pls", "m3u"},
     *     message = "Invalid format."
     * )
     * @ORM\Column(name="format", type="string", length=255)
     */
    private $format = 'pls';

    /**
     * @var boolean
     * @Assert\Type(type="boolean", message="Invalid premium value")
     * @ORM\Column(name="premium", type="boolean")
     */
    private $premium = false;

    /**
     * @var string
     * @Assert\Type(type="string")
     * @ORM\Column(name="listenKey", type="string", length=255)
     */
    private $listenKey = '';

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set site
     * @param string $site
     * @return PlaylistConfiguration
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * Get site
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set format
     * @param string $format
     * @return PlaylistConfiguration
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get format
     * @return string 'm3u'|'pls'
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set premium
     * @param boolean $premium
     * @return PlaylistConfiguration
     */
    public function setPremium($premium)
    {
        $this->premium = $premium === 'premium';
        return $this;
    }

    /**
     * Get premium
     * @return boolean
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * Set listenKey
     * @param string $listenKey
     * @return PlaylistConfiguration
     */
    public function setListenKey($listenKey)
    {
        $listenKey = preg_replace('/[^\da-z]/', '', $listenKey);
        $listenKey = $listenKey === 'playlist' ? '' : $listenKey;
        $this->listenKey = $listenKey;
        return $this;
    }

    /**
     * Get listenKey
     * @return string
     */
    public function getListenKey()
    {
        return $this->listenKey;
    }

    /**
     * Validates the combination of premium toggle and listenKey
     * @param ExecutionContext $context
     */
    public function isPremiumKeyValid(ExecutionContext $context)
    {
        if ($this->premium && empty($this->listenKey)) {
            $context->addViolationAt('listenKey', 'Premium listen key is required when the premium option is selected.');
        }
    }

    /**
     * Return a new instance
     * @return PlaylistConfiguration
     */
    public static function create()
    {
        return new self();
    }
}
