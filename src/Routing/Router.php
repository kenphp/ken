<?php

namespace Ken\Routing;

use Ken\Base\Component;
use Ken\Exception\InvalidConfigurationException;
use Ken\Http\Request;
use Ken\Exception\RouteNotFoundException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Router extends Component
{
    /**
     * @var \Ken\Routing\RouteCollection
     */
    protected $routeCollection;

    protected $routeFile;

    public function __construct(array $config = array())
    {
        if (!isset($config['routeFile'])) {
            throw new InvalidConfigurationException("Parameter 'routeFile' is required in Router component configuration.");
        }
        $this->routeCollection = new RouteCollection();
        $this->setRouteFile($config['routeFile']);
    }

    protected function setRouteFile($filepath)
    {
        $this->routeFile = $filepath;
        $router = $this;
        include_once $this->routeFile;
    }

    public function route($url, callable $target, $method = 'GET', $options = [])
    {
        $route = Route::routeFromConfig(compact('url', 'target', 'method'));

        $this->routeCollection->add($route);
    }

    public function get($url, callable $target, $options = [])
    {
        $this->route($url, $target, 'GET', $options);
    }

    public function post($url, callable $target, $options = [])
    {
        $this->route($url, $target, 'POST', $options);
    }

    public function put($url, callable $target, $options = [])
    {
        $this->route($url, $target, 'PUT', $options);
    }

    public function delete($url, callable $target, $options = [])
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
