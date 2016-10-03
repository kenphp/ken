<?php

namespace Ken\Helper;

/**
 * @author Juliarid <ardi93@gmail.com>
 */
class Log
{
    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, $methodNames)) {
            $logger = app()->logger;
            call_user_func_array([$logger, $name], $arguments);
        }
    }
}
