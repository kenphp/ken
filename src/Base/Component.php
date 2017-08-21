<?php

namespace Ken\Base;

/**
 * Base class for application components
 * @author Juliardi <ardi93@gmail.com>
 */
abstract class Component implements Buildable
{
    abstract public function __construct(array $config = array());

    /**
     * Builds component object
     * @param array $config
     * @return static
     */
    public static function build(array $config = array())
    {
        return new static($config);
    }

    public function __isset($name)
    {
        return property_exists($this, $name);
    }
}
