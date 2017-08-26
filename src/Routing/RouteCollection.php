<?php

namespace Ken\Routing;

use Ken\Exception\DuplicateRouteNameException;
use Ken\Exception\HttpException;

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

    /**
     * Adds route to the collection.
     *
     * @param \Ken\Routing\Route $route
     */
    public function add($route)
    {
        if (!is_null($route->getName())) {
            $routeKey = $route->getName().'-'.$route->getMethod();
            if (array_key_exists($routeKey, $this->arrRoute)) {
                throw new DuplicateRouteNameException(sprintf("Duplicate route : name='%s' method='%s'", $route->getName(), $route->getMethod()));
            }
            $this->arrRoute[$routeKey] = $route;
        } else {
            array_push($this->arrRoute, $route);
        }
    }

    /**
     * Retrieves route by URL and Method.
     *
     * @param string $url    Requested url
     * @param string $method Request method
     */
    public function get($url, $method)
    {
        foreach ($this->arrRoute as $route) {
            if ($route->isMatch($url, $method)) {
                return $route;
            }
        }

        throw new HttpException(404, "Route '$url' with '$method' method not found");
    }
}
