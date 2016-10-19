<?php

namespace Ken\View;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class TwigEngine extends BaseEngine
{
    protected $twig;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->initTwig();
    }

    protected function initTwig()
    {
        $loader = new Twig_Loader_Filesystem($this->viewPath);

        if (!empty($this->cachePath)) {
            $this->twig = new Twig_Environment($loader, array(
                'cache' => $this->cachePath,
            ));
        } else {
            $this->twig = new Twig_Environment($loader);
        }

        $functions = $this->getTwigFunction();

        foreach ($functions as $function) {
            $this->twig->addFunction($function);
        }
    }

    protected function getTwigFunction()
    {
        $functions = array();

        $functions[] = new Twig_SimpleFunction('app', function () {
            return app();
        });

        return $functions;
    }

    public function render(string $view, array $params = [])
    {
        $view = $this->suffixExtension($view);

        echo $this->twig->render($view, $params);
    }

    protected function getFileExtension()
    {
        return 'twig';
    }
}
