<?php

namespace Ken\Helper;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Log
{
    public static function __callStatic($name, $arguments)
    {
        $methodNames = array('emergency', 'alert', 'critical',
                            'error', 'warning', 'notice',
                            'info', 'debug', );

        if (in_array($name, $methodNames)) {
            $logger = app()->logger;
            call_user_func_array([$logger, $name], $arguments);
        }
    }
}
