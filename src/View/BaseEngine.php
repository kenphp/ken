<?php

namespace Ken\View;

use Psr\Http\Message\ResponseInterface;

/**
 * Base class for templating engine.
 *
 * @author Juliardi <ardi93@gmail.com>
 */
abstract class BaseEngine
{

    /**
     * Base path for view files
     * @var string
     */
    protected $viewPath;

    /**
     * An array of function that would be accessable from view file.
     * The array's **key** would be the name of the function.
     * @var array
     */
    protected $viewFunctions;

    /**
     * @var mixed
     */
    protected $engine;

    /**
     * @param string $viewPath  Base path for view files
     * @param array $viewFunctions An array of function that would be accessable from view file.
     * The array's **key** would be the name of the function.
     */
    public function __construct($viewPath, $viewFunctions = [])
    {
        $this->viewPath = rtrim($viewPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->viewFunctions = $viewFunctions;
    }

    /**
     * @param  string $view View file name without extension
     * @return string View file name with extension
     */
    protected function suffixExtension($view)
    {
        return $view.'.'.$this->getFileExtension();
    }

    /**
    * Retrieves templating engine's instance
    * @var mixed
    */
    public function getEngine() {
        return $this->engine;
    }

    /**
     * Inits templating engine.
     */
    abstract protected function initEngine();

    /**
     * Fetch rendered template.
     *
     * @param ResponseInterface $response Http Response object
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     * @return ResponseInterface
     */
    abstract public function render(ResponseInterface $response, $view, array $params = []);

    /**
     * Fetch rendered template.
     *
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     * @return string
     */
    abstract public function fetch($view, array $params = []);

    /**
     * Retrieves template file extension.
     *
     * @return string
     */
    abstract protected function getFileExtension();
}
