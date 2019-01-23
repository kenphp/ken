<?php

namespace Ken\Helpers;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Route
{
    /**
     * Adds route to $routeCollection.
     *
     * @param string  $route
     * @param callable $handler  Target callable that will be called when the route is requested
     * @param string  $method  HTTP Method
     * @param array   $options
     */
    public static function route($route, $handler, $method = 'GET', $options = [])
    {
        app()->router->route($method, $route, $handler, $options);
    }

    /**
     * Adds GET route to $routeCollection.
     *
     * @param string  $route
     * @param callable $handler  Target callable that will be called when the route is requested
     * @param array   $options
     */
    public static function get($route, $handler, $options = [])
    {
        app()->router->get($route, $handler, $options);
    }

    /**
     * Adds POST route to $routeCollection.
     *
     * @param string  $route
     * @param callable $handler  Target callable that will be called when the route is requested
     * @param array   $options
     */
    public static function post($route, $handler, $options = [])
    {
        app()->router->post($route, $handler, $options);
    }

    /**
     * Adds PUT route to $routeCollection.
     *
     * @param string  $route
     * @param callable $handler  Target callable that will be called when the route is requested
     * @param array   $options
     */
    public static function put($route, $handler, $options = [])
    {
        app()->router->put($route, $handler, $options);
    }

    /**
     * Adds DELETE route to $routeCollection.
     *
     * @param string  $route
     * @param callable $handler  Target callable that will be called when the route is requested
     * @param array   $options
     */
    public static function delete($route, $handler, $options = [])
    {
        app()->router->delete($route, $handler, $options);
    }

    /**
     * Adds route with several HTTP methods bind to $routeCollection.
     *
     * @param array   $methods HTTP Methods that apply to the routes
     * @param string  $route
     * @param callable $handler  Target callable that will be called when the route is requested
     * @param array   $options
     */
    public static function map($methods, $route, $handler, $options = [])
    {
        foreach ($methods as $method) {
            app()->router->route($method, $route, $handler, $options);
        }
    }

    /**
     * Adds group route.
     *
     * @param string $route Group base route
     * @param callable $callback
     * @param array   $options
     */
    public static function group($route, $callback, $options = [])
    {
        app()->router->group($route, $callback, $options);
    }
}
