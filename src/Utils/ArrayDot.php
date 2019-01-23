<?php

namespace Ken\Utils;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class ArrayDot implements \ArrayAccess
{
    /**
     * @var array
     */
    private $_arr;

    /**
     * @param array $arr An array
     */
    public function __construct(array $arr)
    {
        $this->_arr = $arr;
    }

    /**
     * Get all config.
     *
     * @return array Array of config
     */
    public function all()
    {
        return $this->_arr;
    }

    /**
     * Gets config value based on key.
     *
     * To access a sub-array you can use a dot separated key.
     * For example, given this configuration array :
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
     * @param string $key           Dot-separated string of key
     * @param mixed $defaultValue   Default value returned when **$key** is not found.
     *
     * @return mixed Value of config
     */
    public function get($key, $defaultValue = null)
    {
        $keys = explode('.', $key);
        $config = $this->_arr;

        foreach ($keys as $value) {
            if (is_array($config)) {
                if (array_key_exists($value, $config)) {
                    $config = $config[$value];
                } else {
                    return $defaultValue;
                }
            } else {
                return $defaultValue;
            }
        }

        return $config;
    }

    /**
     * Sets config value based on key.
     *
     * To set a sub-array configuration, you can use a dot separated key.
     * For example, given this configuration array :
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
     * @param string $key Dot-separated string of key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $keys = explode('.', $key);
        $config = &$this->_arr;

        foreach ($keys as $val) {
            $config = &$config[$val];
        }

        $config = $value;
    }

     /**
      * Removes config value based on key.
      *
      * To unset a sub-array configuration, you can use a dot separated key.
      * For example, given this configuration array :
      *
      *     array(
      *          'basePath' => 'somepath',
      *          'params' => array(
      *              'somekey' => 'value of key'
      *          )
      *     );
      *
      * To unset the value of 'somekey', you can call the method like this :
      *
      *     $config->unset('params.somekey');
      *
      * @param string $key Dot-separated string of key
      */
     public function remove($key)
     {
         $keys = explode('.', $key);
         $cKeys = count($keys);
         $config = &$this->_arr;

         for ($i = 0; $i < $cKeys; ++$i) {
             if ($i === ($cKeys - 1)) {
                 unset($config[$keys[$i]]);
             } elseif (is_array($config)) {
                 if (array_key_exists($keys[$i], $config)) {
                     $config = &$config[$keys[$i]];
                 } else {
                     return;
                 }
             }
         }
     }

    /**
     * Checks whether config has a certain key.
     *
     * @param string $key Dot-separated string of key
     *
     * @return bool
     */
    public function has($key)
    {
        $keys = explode('.', $key);
        $cKeys = count($keys);
        $config = $this->_arr;

        for ($i = 0; $i < $cKeys; ++$i) {
            if (is_array($config)) {
                if (array_key_exists($keys[$i], $config)) {
                    $config = $config[$keys[$i]];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    public function offsetExists($offset) {
        return isset($this->_arr[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->_arr[$offset]) ? $this->_arr[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_arr[] = $value;
        } else {
            $this->_arr[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->_arr[$offset]);
    }
}
