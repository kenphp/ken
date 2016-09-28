<?php

namespace Ken;

use Ken\Exception\FileNotFoundException;

/**
 *
 */
class View
{
    protected $path;

    public function __construct($viewPath)
    {
        if (substr($viewPath, -1) == DIRECTORY_SEPARATOR) {
            $this->path = $viewPath;
        } else {
            $this->path = $viewPath.DIRECTORY_SEPARATOR;
        }
    }

    public function render(string $view, array $params = [])
    {
        try {
            $filepath = $this->findView($view);
            extract($params);
            include $filepath;
        } catch (FileNotFoundException $exc) {
            //insert log
        }
    }

    protected function findView(string $view)
    {
        $viewFilePath = $this->path.$view;
        if (file_exists($this->path.$view)) {
            return $viewFilePath;
        } else {
            throw new FileNotFoundException("File '$viewFilePath' not found");
        }
    }
}
