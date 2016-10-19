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

    /**
     * Constructor.
     *
     * @param Ken\Http\Request $request Instance of Ken\Http\Request class
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->assignPropertyValue($config);
    }

    /**
     * Assign property value.
     */
    private function assignPropertyValue(array $config)
    {
        if (!isset($config['get'])) {
            throw new InvalidConfigurationException("Parameter 'get' is required in Request component configuration");
        } elseif (!isset($config['post'])) {
            throw new InvalidConfigurationException("Parameter 'post' is required in Request component configuration");
        } elseif (!isset($config['files'])) {
            throw new InvalidConfigurationException("Parameter 'files' is required in Request component configuration");
        } else {
            $this->paramGet = $_GET;
            $this->paramPost = $_POST;
            $this->paramFiles = $_FILES;
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
