<?php

namespace Ken\Utils;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Config
{
    private $__config;

    /**
     * @param array $config Configuration array
     */
    public function __construct(array $config)
    {
        $this->__config = $config;
    }

    /**
     * Get all config.
     *
     * @return array Array of config
     */
    public function all()
    {
        return $this->__config;
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
     *     echo $config->get('params.somekey');
     *
     * The code above will print 'value of key' which is the value of
     * config 'somekey' in the 'params' array.
     *
     * @param string $key Key of config
     *
     * @return mixed Value of config
     */
    public function get($key)
    {
        $keys = explode('.', $key);
        $config = $this->__config;

        foreach ($keys as $value) {
            if (is_array($config)) {
                if (array_key_exists($value, $config)) {
                    $config = $config[$value];
                } else {
                    return;
                }
            } else {
                return;
            }
        }

        return $config;
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
     *     $config->set('params.somekey','another value of key');
     *
     * @param string $key Key of config
     *
     * @return mixed Value of config
     */
    public function set($key, $value)
    {
        $keys = explode('.', $key);
        $config = &$this->__config;

        foreach ($keys as $val) {
            $config = &$config[$val];
        }

        $config = $value;
    }
}
