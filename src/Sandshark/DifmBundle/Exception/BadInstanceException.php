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
class BadInstanceException extends \Exception
{
    /**
     * @param string $className
     * @param mixed $object
     */
    public function __construct($className, $object)
    {
        $type = is_object($object) ? get_class($object) : gettype($object);
        $msg = sprintf('%s not an instance of %s', $type, $className);
        parent::__construct($msg);
    }
}
