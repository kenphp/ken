<?php

namespace Ken\View;

use Ken\Base\Buildable;
use Ken\Exception\InvalidConfigurationException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
abstract class BaseEngine implements Buildable
{
    protected $viewPath;
    protected $cachePath;

    public function __construct($config)
    {
        if (!isset($config['path'])) {
            throw new InvalidConfigurationException("Paramter 'path' is required in View component configuration.");
        }

        $viewPath = $config['path'];
        $cachePath = isset($config['cache']) ? $config['cache'] : '';

        if (substr($viewPath, -1) == DIRECTORY_SEPARATOR) {
            $this->viewPath = $viewPath;
        } else {
            $this->viewPath = $viewPath.DIRECTORY_SEPARATOR;
        }

        if (isset($config['cache'])) {
            if (substr($cachePath, -1) == DIRECTORY_SEPARATOR) {
                $this->cachePath = $cachePath;
            } else {
                $this->cachePath = $cachePath.DIRECTORY_SEPARATOR;
            }
        }
    }

    public static function build(array $config = array())
    {
        return new static($config);
    }

    protected function suffixExtension($view)
    {
        return $view.'.'.$this->getFileExtension();
    }

    /**
     * Render a view file.
     *
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    abstract public function render($view, array $params = []);
    abstract protected function getFileExtension();
}
