<?php

namespace Ken\View;

use Ken\Exception\FileNotFoundException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class PhpEngine extends BaseEngine
{
    public function render($view, array $params = [])
    {
        $view = $this->suffixExtension($view);
        try {
            $filepath = $this->findView($view);
            extract($params);
            include $filepath;
        } catch (FileNotFoundException $exc) {
            app()->logger->error($exc->getMessage());
        }
    }

    protected function findView($view)
    {
        $viewFilePath = $this->viewPath.$view;
        if (file_exists($this->viewPath.$view)) {
            return $viewFilePath;
        } else {
            throw new FileNotFoundException("File '$viewFilePath' not found");
        }
    }

    protected function getFileExtension()
    {
        return 'php';
    }
}
