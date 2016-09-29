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
    function render(string $view, $params = [])
    {
        return app()->view->render($view, $params);
    }
}

if (!function_exists('renderTwig')) {
    /**
     * Render a view file.
     *
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    function renderTwig(string $view, $params = [])
    {
        return app()->view->renderTwig($view, $params);
    }
}

if (!function_exists('inputGet')) {
    /**
     * Retrieve GET parameter.
     *
     * @param string $nane Name of GET parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    function inputGet(string $name = null)
    {
        return app()->input->get($name);
    }
}

if (!function_exists('paramPost')) {
    /**
     * Retrieve POST parameter.
     *
     * @param string $nane $name of POST parameter, if null then all parameter will be returned
     *
     * @return mixed
     */
    function inputPost(string $name = null)
    {
        return app()->input->post($name);
    }
}

if (!function_exists('paramPut')) {
    /**
      * Retrieve PUT parameter.
      *
      * @param string $nane Name of PUT parameter, if null then all parameter will be returned
      *
      * @return mixed
      */
     function inputPut(string $name)
     {
         return app()->input->put($name);
     }
}

if (!function_exists('paramDelete')) {
    /**
      * Retrieve DELETE parameter.
      *
      * @param string $nane Name of DELETE parameter, if null then all parameter will be returned
      *
      * @return mixed
      */
     function inputDelete(string $name = null)
     {
         return app()->input->delete($name);
     }
}
