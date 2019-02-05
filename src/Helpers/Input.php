<?php

namespace Ken\Helpers;

/**
 * Juliardi <ardi93@gmail.com>.
 */
class Input
{
    /**
     * Retrieves GET parameter.
     *
     * @param string $name Name of GET parameter, if null then all parameter will be returned
     * @param mixed $default Default value returned if *$name* is not null but does not exist in GET query parameters
     * @return mixed
     */
    public static function get($name = null, $default = null)
    {
        $queryParams = app()->request->getQueryParams();

        if (is_null($name)) {
            return $queryParams;
        } elseif (isset($queryParams[$name])) {
            return $queryParams[$name];
        }

        return $default;
    }

    /**
     * Retrieves POST parameter.
     *
     * @param string $name Name of POST parameter, if null then all parameter will be returned
     * @param mixed $default Default value returned if *$name* is not null but does not exist in POST request
     *
     * @return mixed
     */
    public static function post($name = null, $default = null)
    {
        $parsedBody = app()->request->getParsedBody();

        if (is_null($name)) {
            return $parsedBody;
        }

        if (isset($parsedBody[$name])) {
            return $parsedBody[$name];
        }

        return $default;
    }

    /**
     * Retrieves DELETE parameter.
     *
     * @param string $name Name of DELETE parameter, if null then all parameter will be returned
     * @param mixed $default Default value returned if *$name* is not null but does not exist in DELETE request
     *
     * @return mixed
     */
    public static function delete($name = null, $default = null)
    {
        $parsedBody = app()->request->getParsedBody();

        if (is_null($name)) {
            return $parsedBody;
        }

        if (isset($parsedBody[$name])) {
            return $parsedBody[$name];
        }

        return $default;
    }

    /**
     * Retrieves PUT parameter.
     *
     * @param string $name Name of PUT parameter, if null then all parameter will be returned
     * @param mixed $default Default value returned if *$name* is not null but does not exist in PUT request
     *
     * @return mixed
     */
    public static function put($name = null, $default = null)
    {
        $parsedBody = app()->request->getParsedBody();

        if (is_null($name)) {
            return $parsedBody;
        }

        if (isset($parsedBody[$name])) {
            return $parsedBody[$name];
        }

        return $default;
    }

    /**
     * Retrieves Uploaded Files.
     *
     * @param string $name Name of Uploaded Files parameter, if null then all value will be returned
     *
     * @return \Psr\Http\Message\UploadedFileInterface|array|null
     */
    public static function files($name = null)
    {
        $files = app()->request->getUploadedFiles();

        if (is_null($name)) {
            return $files;
        }

        if (isset($files[$name])) {
            return $files[$name];
        }

        return null;
    }
}
