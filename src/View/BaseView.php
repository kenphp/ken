<?php

namespace Ken\View;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
abstract class BaseView
{
    protected $viewPath;
    protected $cachePath;
    protected static $instance;

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

    abstract public function render(string $view, array $params = []);

    public static function newFromConfig($className, $config)
    {
        self::$instance = new $className($config);

        return self::$instance;
    }

    public static function getInstance($className, $config)
    {
        return $className::newFromConfig($config);
    }
}
