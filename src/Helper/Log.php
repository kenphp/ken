<?php

namespace Ken\Helper;

use Ken\Application;

/**
 * @author Juliarid <ardi93@gmail.com>
 */
class Log
{
    public static function __callStatic($name, $arguments)
    {
        $methodNames = ['log', 'info', 'emergency',
                        'alert', 'critical', 'warning',
                        'notice', 'debug', ];
        $app = Application::getInstance();

        if (in_array($name, $methodNames)) {
            $logger = $app->logger;
            call_user_func_array([$logger, $name], $arguments);
        }
    }
}
