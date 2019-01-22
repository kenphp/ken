<?php

namespace Ken\Helpers;

use Ken\Application;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class View
{
    /**
     * Render a view file.
     *
     * @param ResponseInterface $response
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    public static function render(ResponseInterface $response, $view, array $params = [])
    {
        return Application::getInstance()->view->render($response, $view, $params);
    }
}
