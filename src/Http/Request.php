<?php

namespace Ken\Http;

use Ken\Base\Component;
use Ken\Exception\InvalidConfigurationException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Request extends Component
{
    protected $pathInfo;
    protected $method;
    protected $referer;
    protected $baseUrl;
    protected $requestUri;

    public function __construct(array $config = array())
    {
        if (!isset($config['server'])) {
            throw new InvalidConfigurationException("Parameter 'server' is required in Request component configuration");
        }
        $this->assignPropertyValue($config['server']);
    }

    protected function assignPropertyValue($server)
    {
        $this->method = $server['REQUEST_METHOD'];
        $this->referer = isset($server['HTTP_REFERER']) ? $server['HTTP_REFERER'] : '';
        $this->pathInfo = $this->generatePathInfo($server);
        $this->baseUrl = $server['HTTP_HOST'];
        $this->requestUri = $server['REQUEST_URI'];
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            $methodName = 'get'.ucfirst($name);

            return $this->$methodName();
        }
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    private function generatePathInfo($server)
    {
        if (!array_key_exists('PATH_INFO', $server)) {
            if (empty($server['QUERY_STRING'])) {
                return $server['REQUEST_URI'];
            } else {
                $pos = strpos($server['REQUEST_URI'], $server['QUERY_STRING']);

                $pathInfo = substr($server['REQUEST_URI'], 0, $pos - 1);

                return $pathInfo;
            }
        } else {
            return $server['PATH_INFO'];
        }
    }

    public function isGetRequest()
    {
        return $this->method == 'GET';
    }

    public function isPostRequest()
    {
        return $this->method == 'POST';
    }
    public function isPutRequest()
    {
        return $this->method == 'PUT';
    }
    public function isDeleteRequest()
    {
        return $this->method == 'DElete';
    }

    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getReferer()
    {
        return $this->referer;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getRequestUri()
    {
        return $this->requestUri;
    }
}
