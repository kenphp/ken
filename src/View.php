<?php

namespace Ken;

use Ken\Exception\FileNotFoundException;

/**
 *
 */
class View
{
    protected $viewPath;
    protected $cachePath;
    protected $twig;

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

        $this->initTwig();
    }

    protected function initTwig()
    {
        $loader = new \Twig_Loader_Filesystem($this->viewPath);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => $this->cachePath,
        ));
    }

    public function renderTwig(string $view, array $params = [])
    {
        echo $this->twig->render($view, $params);
    }

    public function render(string $view, array $params = [])
    {
        try {
            $filepath = $this->findView($view);
            extract($params);
            include $filepath;
        } catch (FileNotFoundException $exc) {
            app()->logger->error($exc->getMessage());
        }
    }

    protected function findView(string $view)
    {
        $viewFilePath = $this->viewPath.$view;
        if (file_exists($this->viewPath.$view)) {
            return $viewFilePath;
        } else {
            throw new FileNotFoundException("File '$viewFilePath' not found");
        }
    }
}
