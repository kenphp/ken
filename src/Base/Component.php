<?php

namespace Ken\Base;

/**
 *
 */
abstract class Component implements Buildable
{
    abstract public function __construct(array $config = array());

    public static function build(array $config = array())
    {
        return new static($config);
    }
}
