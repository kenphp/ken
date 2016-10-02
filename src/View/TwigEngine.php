<?php

namespace Ken\View;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class TwigEngine extends BaseView
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
        $this->twig = new Twig_Environment($loader, array(
            'cache' => $this->cachePath,
        ));
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
        echo $this->twig->render($view, $params);
    }
}
