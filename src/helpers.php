<?php

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
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    function render($view, $params = [])
    {
        return app()->view->render($view, $params);
    }
}
