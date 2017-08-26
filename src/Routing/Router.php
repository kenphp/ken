<?php

namespace Ken\Routing;

use Closure;
use Ken\Base\Component;
use Ken\Exception\InvalidConfigurationException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Router extends Component
{
    /**
     * @var \Ken\Routing\RouteCollection
     */
    protected $routeCollection;

    /**
     * @var string
     */
    protected $routeFile;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param array $config
     * @return static
     */
    public function __construct(array $config = array())
    {
        $this->routeCollection = new RouteCollection();
    }

    /**
     * Sets route file path.
     *
     * @param string $filepath
     */
    public function setRouteFile($filepath)
    {
        $this->routeFile = $filepath;
    }

    /**
     * Registers application routes defined in route file.
     */
    public function registerRoutes()
    {
        try {
            include_once $this->routeFile;
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
    }

    /**
     * Adds route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param string  $method  HTTP Method
     * @param array   $options
     */
    public function addRoute($url, $target, $method = 'GET', $options = [])
    {
        if (!empty($this->prefix) && ($this->prefix != $url)) {
            $url = $this->prefix.$url;
        }

        $target = $this->convertCallbackToClosure($target, $this->namespace);

        $options['url'] = $url;
        $options['target'] = $target;
        $options['method'] = $method;

        $route = Route::routeFromConfig($options);

        $this->routeCollection->add($route);
    }

    /**
     * Adds GET route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public function get($url, $target, $options = [])
    {
        $this->addRoute($url, $target, 'GET', $options);
    }

    /**
     * Adds POST route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public function post($url, $target, $options = [])
    {
        $this->addRoute($url, $target, 'POST', $options);
    }

    /**
     * Adds PUT route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public function put($url, $target, $options = [])
    {
        $this->addRoute($url, $target, 'PUT', $options);
    }

    /**
     * Adds DELETE route to $routeCollection.
     *
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public function delete($url, $target, $options = [])
    {
        $this->addRoute($url, $target, 'DELETE', $options);
    }

    /**
     * Adds route with several HTTP methods bind to $routeCollection.
     *
     * @param array   $methods HTTP Methods that apply to the routes
     * @param string  $url
     * @param Closure $target  Target Closure that will be called when the route is requested
     * @param array   $options
     */
    public function map($methods, $url, $target, $options = [])
    {
        foreach ($methods as $value) {
            $this->addRoute($url, $target, $value, $options);
        }
    }

    /**
     * Adds group route.
     *
     * @param array   $attributes
     * @param Closure $callback
     */
    public function group(array $attributes, $callback)
    {
        if (empty($attributes)) {
            throw new InvalidConfigurationException(sprintf("Parameter 'attributes' must not empty in group routes configuration."));
        }

        $tempPrefix = $this->prefix;
        $tempNamespace = $this->namespace;

        if (isset($attributes['prefix'])) {
            $this->prefix = $tempPrefix.$attributes['prefix'];
        }
        if (isset($attributes['namespace'])) {
            $this->namespace = $tempNamespace.$attributes['namespace'];
        }

        if ($callback instanceof Closure) {
            $callback->bindTo($this, self::class);
        }

        try {
            call_user_func($callback, $this);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit(1);
        }

        $this->prefix = $tempPrefix;
        $this->namespace = $tempNamespace;
    }

    /**
     * Converts callback to Closure.
     *
     * @param string|Closure $callback
     * @param string         $namespace
     *
     * @return Closure
     */
    protected function convertCallbackToClosure($callback, $namespace)
    {
        if ($callback instanceof Closure) {
            return $callback;
        } elseif (is_string($callback)) {
            $namespace = rtrim($namespace, '\\').'\\';
            $arrCallback = explode('::', $callback);
            $isStaticCall = count($arrCallback) == 2;

            if ($isStaticCall) {
                $className = $namespace.$arrCallback[0];

                return [$className, $arrCallback[1]];
            } else {
                $arrCallback = explode(':', $callback);
                $className = $namespace.$arrCallback[0];
                // Must be replaced with a safer way to instantiate an object
                $obj = new $className();

                return [$obj, $arrCallback[1]];
            }
        }
    }

    /**
     * Handles HTTP Request.
     *
     * @param \Ken\Http\Request $request
     */
    public function handleRequest($request)
    {
        $pathInfo = $request->pathInfo;
        $method = $request->method;

        $route = $this->routeCollection->get($pathInfo, $method);
        $route->dispatch();
    }
}
