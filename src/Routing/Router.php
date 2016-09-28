<?php

namespace Ken\Routing;

use Ken\Http\Request;
use Ken\Exception\RouteNotFoundException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Router
{
    /**
     * @property RouteCollection $routeCollection Object of RouteCollection
     */
    protected $routeCollection;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection();
    }

    public function route(string $url, callable $target, $method = 'GET', $options = [])
    {
        $route = Route::routeFromConfig(compact('url', 'target', 'method'));

        $this->routeCollection->add($route);
    }

    public function get(string $url, callable $target, $options = [])
    {
        $this->route($url, $target, 'GET', $options);
    }

    public function post(string $url, callable $target, $options = [])
    {
        $this->route($url, $target, 'POST', $options);
    }

    public function put(string $url, callable $target, $options = [])
    {
        $this->route($url, $target, 'PUT', $options);
    }

    public function delete(string $url, callable $target, $options = [])
    {
        $this->route($url, $target, 'DELETE', $options);
    }

    public function handleRequest(Request $request)
    {
        $pathInfo = $request->pathInfo;
        $method = $request->method;

        // try {
        $route = $this->routeCollection->get($pathInfo, $method);
        $route->dispatch();
        // } catch (RouteNotFoundException $exc) {
        //     // echo $exc->getMessage();
        //     // echo '<br>';
        //     // echo $exc->getTraceAsString();
        //     include_once '../test/404.php';
        // }
    }
}
