<?php

namespace Ken\Helper;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Route
{
    /**
     * Adds route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param string  $method  HTTP Method
     * @param array   $options
     */
    public static function route($url, $target, $method = 'GET', $options = [])
    {
        app()->router->addRoute($url, $target, $method, $options);
    }

    /**
     * Adds GET route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public static function get($url, $target, $options = [])
    {
        app()->router->get($url, $target, $options);
    }

    /**
     * Adds POST route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public static function post($url, $target, $options = [])
    {
        app()->router->post($url, $target, $options);
    }

    /**
     * Adds PUT route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public static function put($url, $target, $options = [])
    {
        app()->router->put($url, $target, $options);
    }

    /**
     * Adds DELETE route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public static function delete($url, $target, $options = [])
    {
        app()->router->delete($url, $target, $options);
    }

    /**
     * Adds route with several HTTP methods bind to $routeCollection.
     *
     * @param array   $methods HTTP Methods that apply to the routes
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public static function map($methods, $url, $target, $options = [])
    {
        app()->router->map($methods, $url, $target, $options);
    }

    /**
     * Adds group route.
     *
     * @param array   $options
     * @param Closure $callback
     */
    public static function group($options, $callback)
    {
        app()->router->group($options, $callback);
    }
}
