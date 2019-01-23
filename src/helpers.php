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

if (!function_exists('renderJson')) {
    /**
     * Render a JSON string.
     *
     * @param ResponseInterface $response
     * @param array $array An array to be converted to JSON string
     * @return ResponseInterface
     */
    function renderJson(ResponseInterface $response, array $array)
    {
        $response->getBody()->write(json_encode($array));

        return $response->withHeader('Content-type', 'application/json');
    }
}
