<?php
use Psr\Http\Message\ResponseInterface;


if (!function_exists('app')) {

    /**
     * Get application instance.
     *
     * @return Ken\Application
     */
    function app()
    {
        return Ken\Application::getInstance();
    }
}

if (!function_exists('render')) {
    /**
     * Render a view file.
     *
     * @param ResponseInterface $response
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    function render(ResponseInterface $response, $view, $params = [])
    {
        return app()->view->render($response, $view, $params);
    }
}
