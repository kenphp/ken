<?php

namespace Ken\Http;

/**
 * Class Ken\Http\Input
 * Used to retrieve a HTTP Input using GET, POST, PUT, or DELETE method.
 *
 * @author Juliardi <ardi93@gmail.com>
 */
class Input
{
    protected $paramGet;
    protected $paramPost;
    protected $paramPut;
    protected $paramDelete;
    protected $paramFiles;
    private $request;

    /**
     * Constructor.
     *
     * @param Ken\Http\Request $request Instance of Ken\Http\Request class
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->assignPropertyValue();
    }

    /**
     * Assign property value.
     */
    private function assignPropertyValue()
    {
        $this->paramGet = $_GET;
        $this->paramPost = $_POST;
        $this->paramFiles = $_FILES;
        $this->populatePutParam();
        $this->populateDeleteParam();
    }

    /**
     * Populate parameter for PUT request into $paramPut property.
     */
    private function populatePutParam()
    {
        if ($this->request->isPutRequest()) {
            parse_str(file_get_contents('php://input'), $this->paramPut);
        } else {
            $this->paramPut = [];
        }
    }

    /**
     * Populate parameter for DELETE request into $paramDelete property.
     */
    private function populateDeleteParam()
    {
        if ($this->request->isDeleteRequest()) {
            parse_str(file_get_contents('php://input'), $this->paramDelete);
        } else {
            $this->paramDelete = [];
        }
    }

    /**
     * Retrieve GET parameter.
     *
     * @param string $name Name of GET parameter, if null then all parameter will be returned
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
     * Retrieve POST parameter.
     *
     * @param string $name $name of POST parameter, if null then all parameter will be returned
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
     * Retrieve PUT parameter.
     *
     * @param string $name Name of PUT parameter, if null then all parameter will be returned
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
     * Retrieve DELETE parameter.
     *
     * @param string $name Name of DELETE parameter, if null then all parameter will be returned
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
     * Retrieve Uploaded Files.
     *
     * @param string $name Name of Uploaded Files parameter, if null then all parameter will be returned
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
