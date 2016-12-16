<?php

namespace Ken\Helper;

use Ken\Application;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class View
{
    /**
     * Render a view file.
     *
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    public static function render($view, array $params = [])
    {
        return Application::getInstance()->view->render($view, $params);
    }
}
