<?php

namespace Ken\View;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
abstract class BaseView
{
    protected $viewPath;
    protected $cachePath;

    public function __construct($config)
    {
        $viewPath = $config['path'];
        $cachePath = $config['cache'];

        if (substr($viewPath, -1) == DIRECTORY_SEPARATOR) {
            $this->viewPath = $viewPath;
        } else {
            $this->viewPath = $viewPath.DIRECTORY_SEPARATOR;
        }

        if (substr($cachePath, -1) == DIRECTORY_SEPARATOR) {
            $this->cachePath = $cachePath;
        } else {
            $this->cachePath = $cachePath.DIRECTORY_SEPARATOR;
        }
    }

    public static function newFromConfig($className, $config)
    {
        return new $className($config);
    }

    protected function suffixExtension(string $view)
    {
        return $view.'.'.$this->getFileExtension();
    }

    /**
     * Render a view file.
     *
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    abstract public function render(string $view, array $params = []);
    abstract protected function getFileExtension();
}
