<?php

namespace Ken\Helpers;

use Psr\Log\InvalidArgumentException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class ComponentFactory
{
    /**
     * Creates an object.
     *
     * @param mixed $builder Builder can be an instance of callable that returns an object,
     *                       or a string that contains a fully qualified class name that implements
     *                       Ken\Base\Buildable interface
     *
     * @param array $parameters List of parameters for creating object
     * @return object
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public static function createObject($builder, $parameters = array())
    {
        if (is_callable($builder)) {
            return call_user_func_array($builder, $parameters);
        } elseif (is_string($builder)) {
            if (is_subclass_of($builder, '\\Ken\\Base\\Buildable')) {
                return $builder::build($parameters);
            }
        }

        throw new InvalidArgumentException("Invalid argument when calling 'createObject' method.");
    }
}
