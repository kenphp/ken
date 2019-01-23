<?php

namespace Ken\Helpers;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Config
{
    /**
     * Get all config.
     *
     * @return array Array of config
     */
    public static function all()
    {
        return app()->configuration->all();
    }

    /**
     * Gets config value based on key.
     *
     * To access a sub-array you can use a dot separated key.
     * For example, given this configuration array :.
     *
     *     array(
     *          'basePath' => 'somepath',
     *          'params' => array(
     *              'somekey' => 'value of key'
     *          )
     *     );
     *
     * To access the value of 'somekey', you can call the method like this :
     *
     *     echo $configuration->get('params.somekey');
     *
     * The code above will print 'value of key' which is the value of
     * config 'somekey' in the 'params' array.
     *
     * @param string $key Key of config
     *
     * @return mixed Value of config
     */
    public static function get($key)
    {
        return app()->configuration->get($key);
    }

    /**
     * Sets config value based on key.
     *
     * To set a sub-array configuration, you can use a dot separated key.
     * For example, given this configuration array :.
     *
     *     array(
     *          'basePath' => 'somepath',
     *          'params' => array(
     *              'somekey' => 'value of key'
     *          )
     *     );
     *
     * To set the value of 'somekey' to 'another value of key', you can call the method like this :
     *
     *     $configuration->set('params.somekey','another value of key');
     *
     * @param string $key Key of config
     * @param string $value Value of config
     *
     * @return mixed Value of config
     */
    public static function set($key, $value)
    {
        return app()->configuration->set($key, $value);
    }
}
