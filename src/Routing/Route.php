<?php

namespace Ken\Routing;

use Ken\Exception\InvalidConfigurationException;

/**
 *  @author Juliardi <ardi93@gmail.com>
 */
class Route
{
    protected $name;
    protected $url;
    protected $target;
    protected $before;
    protected $after;
    protected $method = 'GET';
    protected $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    public function __construct(string $url, callable $target)
    {
        $this->setUrl($url);
        $this->setTarget($target);
    }

    public static function routeFromConfig($config)
    {
        if (!isset($config['url']) || !isset($config['target'])) {
            throw new InvalidConfigurationException("Parameter 'url' or 'target' not found");
        }

        $route = new self($config['url'], $config['target']);

        $route->setName(isset($config['name']) ? $config['name'] : $config['url']);

        if (isset($config['before'])) {
            $route->setBefore($config['before']);
        }
        if (isset($config['after'])) {
            $route->setBefore($config['after']);
        }
        if (isset($config['method'])) {
            $route->setMethod($config['method']);
        }

        return $route;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            $methodName = 'get'.ucfirst($name);
            if (method_exists($this, $methodName)) {
                return $this->$methodName();
            }
        }
    }

    public function setName($name)
    {
        $this->name = (string) $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setTarget(callable $target)
    {
        $this->target = $target;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setBefore(callable $before)
    {
        $this->before = $before;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function setAfter(callable $after)
    {
        $this->after = $after;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function setMethod(string $method)
    {
        if (in_array($method, $this->allowedMethods)) {
            $this->method = $method;
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function dispatch()
    {
        if (isset($this->before)) {
            call_user_func($this->before);
        }

        call_user_func($this->target);

        if (isset($this->after)) {
            call_user_func($this->after);
        }
    }
}
