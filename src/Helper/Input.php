<?php

namespace Ken\Helper;

/**
 * Juliardi <ardi93@gmail.com>.
 */
class Input
{
    /**
     * Retrieve GET parameter.
     *
     * @param string $nane Name of GET parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function get(string $name = null)
    {
        return app()->input->get($name);
    }

    /**
     * Retrieve POST parameter.
     *
     * @param string $nane $name of POST parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function post(string $name = null)
    {
        return app()->input->post($name);
    }

    /**
     * Retrieve DELETE parameter.
     *
     * @param string $nane Name of DELETE parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function delete(string $name = null)
    {
        return app()->input->delete($name);
    }

    /**
     * Retrieve PUT parameter.
     *
     * @param string $nane Name of PUT parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    public static function put(string $name = null)
    {
        return app()->input->put($name);
    }
}
