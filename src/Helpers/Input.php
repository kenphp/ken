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
     * @param string $nane Name of GET parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function get($name = null)
    {
        return app()->request->get($name);
    }

    /**
     * Retrieves POST parameter.
     *
     * @param string $nane $name of POST parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function post($name = null)
    {
        return app()->request->post($name);
    }

    /**
     * Retrieves DELETE parameter.
     *
     * @param string $nane Name of DELETE parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function delete($name = null)
    {
        return app()->request->delete($name);
    }

    /**
     * Retrieves PUT parameter.
     *
     * @param string $nane Name of PUT parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function put($name = null)
    {
        return app()->request->put($name);
    }

    /**
     * Retrieves Uploaded Files.
     *
     * @param string $name Name of Uploaded Files parameter, if null then all value will be returned
     *
     * @return mixed
     */
    public static function files($name = null)
    {
        return app()->request->files($name);
    }
}
