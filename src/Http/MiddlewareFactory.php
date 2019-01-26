<?php

namespace Ken\Http;

use InvalidArgumentException;
use Ken\Factory\FactoryInterface;

class MiddlewareFactory extends FactoryInterface {

    /**
     * Creates a middleware instance
     * @param  string $builder    A fully qualified namespace of middleware class.
     * The middleware class must extend **Ken\Http\BaseMiddleware** class.
     * @param  array  $parameters An array containing :
     * 'response' : an instance of **Psr\Http\Message\ResponseInterface**
     * 'next' : null or an instance of **Ken\Http\BaseMiddleware**
     * @return \Ken\Http\BaseMiddleware
     * @throws InvalidArgumentException
     */
    public static function createObject($builder, $parameters = array()) {
        if (is_string($builder)) {
            if (class_exists($builder)) {
                $response = $parameters['response'];
                $nextMiddleware = $parameters['next'];

                if ($response == null) {
                    throw new InvalidArgumentException("'response' parameters must be 'Psr\Http\Message\ResponseInterface' instance.");
                }

                if ($nextMiddleware != null) {
                    if (($nextMiddleware instanceof BaseMiddleware) == false) {
                        throw new InvalidArgumentException("'next' parameters must be 'Ken\Http\BaseMiddleware' instance.");
                    }
                }

                return new $builder($response, $nextMiddleware);
            }

            throw new InvalidArgumentException("Class '{$builder}' not found.");
        }

        throw new InvalidArgumentException("'builder' must be a fully qualified namespace of middleware class.");
    }
}
