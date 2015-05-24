<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 22/05/15
 * Time: 22:13
 */

namespace Sandshark\DifmBundle\Exception;

/**
 * Class BadInterfaceException
 * Throw an exception that an interface is not implemented
 * @package Sandshark\DifmBundle\Exception
 */
class BadInterfaceException extends \Exception
{
    /**
     * @param string $interfaceName
     * @param mixed $object
     */
    public function __construct($interfaceName, $object)
    {
        $type = is_object($object) ? get_class($object) : gettype($object);
        $msg = sprintf('Interface %s was not implemented on %s', $interfaceName, $type);
        parent::__construct($msg);
    }
}
