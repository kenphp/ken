<?php

namespace Ken\Http;

use Ken\Base\Component;
use Ken\Exception\InvalidConfigurationException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Request extends Component
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $pathInfo;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $referer;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $requestUri;

    public function __construct(array $config = array())
    {
        $this->assignPropertyValue($config);
    }

    /**
     * Assigns property value of this class
     * @param array $config
     */
    protected function assignPropertyValue(array $config)
    {
        if (!array_key_exists('server', $config)) {
            throw new InvalidConfigurationException("Parameter 'server' is required in Request component configuration");
        }

        $server = $config['server'];
        $this->method = $server['REQUEST_METHOD'];
        $this->referer = isset($server['HTTP_REFERER']) ? $server['HTTP_REFERER'] : '';
        $this->pathInfo = $this->generatePathInfo($server);
        $this->baseUrl = $this->generateBaseUrl($server);
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

    /**
     * Generates path info value
     * @param array $server The value of $_SERVER global variable
     * @return string Path info value
     */
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

    /**
     * Generates base url of application
     * @param array $server The value of $_SERVER global variable
     * @return string The value of base url
     */
    private function generateBaseUrl($server)
    {
        $protocol = (isset($server['HTTPS']) && $server['HTTPS'] != 'off') ? 'https' : 'http';
        $serverName = $server['SERVER_NAME'];
        $serverPort = (int) $server['SERVER_PORT'];

        if ($serverPort !== 80 || $serverPort !== 443) {
            return sprintf('%s://%s', $protocol, $serverName);
        } else {
            return sprintf('%s://%s:%s', $protocol, $serverName, $serverPort);
        }
    }

    /**
     * Checks whether a request's method is 'GET'
     * @return bool
     */
    public function isGetRequest()
    {
        return $this->method == 'GET';
    }

    /**
     * Checks whether a request's method is 'POST'
     * @return bool
     */
    public function isPostRequest()
    {
        return $this->method == 'POST';
    }

    /**
     * Checks whether a request's method is 'PUT'
     * @return bool
     */
    public function isPutRequest()
    {
        return $this->method == 'PUT';
    }

    /**
     * Checks whether a request's method is 'DELETE'
     * @return bool
     */
    public function isDeleteRequest()
    {
        return $this->method == 'DELETE';
    }

    /**
     * Checks whether a request is AJAX request
     * @return bool
     */
    public function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Retrieves path info value
     * @return string
     */
    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    /**
     * Retrieves request method
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Retrieves refererer Url
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    public function getBaseUrl()
    /**
    * Retrieves base url value
    * @return string
    */
    {
        return $this->baseUrl;
    }

    /**
     * Retrieves request Uri value
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }
}
