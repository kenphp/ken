<?php

namespace Ken\Factory;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
interface FactoryInterface
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
    public function createObject($builder, $parameters = array());
}
