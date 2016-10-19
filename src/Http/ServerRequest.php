<?php

namespace Ken\Http;

use Ken\Exception\InvalidConfigurationException;

/**
 * Used to retrieve a HTTP Input using GET, POST, PUT, or DELETE method.
 *
 * @author Juliardi <ardi93@gmail.com>
 */
class ServerRequest extends Request
{
    protected $paramGet;
    protected $paramPost;
    protected $paramPut;
    protected $paramDelete;
    protected $paramFiles;

    public function __construct(array $config = array())
    {
        $this->assignPropertyValue($config);
    }

    /**
     * Assign property value.
     */
    protected function assignPropertyValue(array $config)
    {
        parent::assignPropertyValue($config);

        if (!array_key_exists('get', $config)) {
            throw new InvalidConfigurationException("Parameter 'get' is required in Request component configuration");
        } elseif (!array_key_exists('post', $config)) {
            throw new InvalidConfigurationException("Parameter 'post' is required in Request component configuration");
        } elseif (!array_key_exists('files', $config)) {
            throw new InvalidConfigurationException("Parameter 'files' is required in Request component configuration");
        } else {
            $this->paramGet = $config['get'];
            $this->paramPost = $config['post'];
            $this->paramFiles = $config['post'];
            $this->populatePutParam();
            $this->populateDeleteParam();
        }
    }

    /**
     * Populates parameter for PUT request into $paramPut property.
     */
    private function populatePutParam()
    {
        if ($this->isPutRequest()) {
            parse_str(file_get_contents('php://input'), $this->paramPut);
        } else {
            $this->paramPut = [];
        }
    }

    /**
     * Populates parameter for DELETE request into $paramDelete property.
     */
    private function populateDeleteParam()
    {
        if ($this->isDeleteRequest()) {
            parse_str(file_get_contents('php://input'), $this->paramDelete);
        } else {
            $this->paramDelete = [];
        }
    }

    /**
     * Retrieves GET parameter.
     *
     * @param string $name Name of GET parameter, if null then all value will be returned
     *
     * @return mixed
     */
    public function get($name = null)
    {
        if (is_null($name)) {
            return $this->paramGet;
        } elseif (isset($this->paramGet[$name])) {
            return $this->paramGet[$name];
        }

        return;
    }

    /**
     * Retrieves POST parameter.
     *
     * @param string $name $name of POST parameter, if null then all value will be returned
     *
     * @return mixed
     */
    public function post($name = null)
    {
        if (is_null($name)) {
            return $this->paramPost;
        } elseif (isset($this->paramPost[$name])) {
            return $this->paramPost[$name];
        }

        return;
    }

    /**
     * Retrieves PUT parameter.
     *
     * @param string $name Name of PUT parameter, if null then all value will be returned
     *
     * @return mixed
     */
    public function put($name = null)
    {
        if (is_null($name)) {
            return $this->paramPut;
        } elseif (isset($this->paramPut[$name])) {
            return $this->paramPut[$name];
        }

        return;
    }

    /**
     * Retrieves DELETE parameter.
     *
     * @param string $name Name of DELETE parameter, if null then all value will be returned
     *
     * @return mixed
     */
    public function delete($name = null)
    {
        if (is_null($name)) {
            return $this->paramDelete;
        } elseif (isset($this->paramDelete[$name])) {
            return $this->paramDelete[$name];
        }

        return;
    }

    /**
     * Retrieves Uploaded Files.
     *
     * @param string $name Name of Uploaded Files parameter, if null then all value will be returned
     *
     * @return mixed
     */
    public function files($name = null)
    {
        if (is_null($name)) {
            return $this->paramFiles;
        } elseif (isset($this->paramFiles[$name])) {
            return $this->paramFiles[$name];
        }

        return;
    }
}
