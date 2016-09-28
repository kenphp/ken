<?php

namespace Ken\Routing;

use Ken\Exception\DuplicateRouteNameException;
use Ken\Exception\RouteNotFoundException;

/**
 *  @author Juliardi <ardi93@gmail.com>
 */
class RouteCollection
{
    protected $arrRoute;

    public function __construct()
    {
        $this->arrRoute = [];
    }

    public function add(Route $route)
    {
        if (!is_null($route->getName())) {
            if (array_key_exists($route->getName(), $this->arrRoute)) {
                throw new DuplicateRouteNameException("Duplicate route name '$route->getName()'");
            }
            $this->arrRoute[$route->getName()] = $route;
        } else {
            array_push($this->arrRoute, $route);
        }
    }

    /**
     * @param string $url    Requested url
     * @param string $method Request method
     */
    public function get(string $url, string $method)
    {
        foreach ($this->arrRoute as $route) {
            if ($route->url == $url && $route->method == $method) {
                return $route;
            }
        }

        throw new RouteNotFoundException("Route '$url' with '$method' method not found");
    }
}
