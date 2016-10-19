<?php

namespace Ken\Helper;

use Psr\Log\InvalidArgumentException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class ComponentFactory
{
    /**
     * Creates an object.
     *
     * @param mixed $builder Builder can be an instance of callable, which should return an object
     *                       or a string which contains fully qualified class name that implements
     *                       Ken\Base\Buildable interface
     *
     * @return object
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public static function createObject($builder, $parameters = array())
    {
        if (is_callable($builder)) {
            return call_user_func_array($builder, $parameters);
        } elseif (is_string($builder)) {
            return $builder::build($parameters);
        } else {
            throw new InvalidArgumentException("Invalid argument when calling 'createObject' method.");
        }
    }
}
