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
     * @return ResponseInterface
     */
    public static function render(ResponseInterface $response, $view, array $params = [])
    {
        return Application::getInstance()->view->render($response, $view, $params);
    }

    /**
     * Render a JSON string.
     *
     * @param ResponseInterface $response
     * @param array $array An array to be converted to JSON string
     * @return ResponseInterface
     */
    public static function renderJson(ResponseInterface $response, array $array)
    {
        $response->getBody()->write(json_encode($array));

        return $response->withHeader('Content-type', 'application/json');
    }
}
