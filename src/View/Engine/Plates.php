<?php

namespace Ken\View\Engine;

use Ken\View\BaseEngine;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;

class Plates extends BaseEngine {

    /**
     * @var \League\Plates\Engine
     */
    protected $engine;

    /**
     * @inheritDoc
     */
    protected function initEngine() {
        $this->engine = new Engine($this->viewPath, $this->getFileExtension());

        foreach ($this->viewFunctions as $name => $fn) {
            if (is_callable($fn)) {
                $this->engine->registerFunction($name, $fn);
            }
        }
    }

    /**
    * @inheritDoc
    */
    protected function getFileExtension() {
        return 'php';
    }

    /**
     * @inheritDoc
     */
    public function render(ResponseInterface $response, $view, array $params = []) {
        $template = $this->fetch($view, $params);
        $response->getBody()->write($template);

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function fetch($view, array $params = []) {
        return $this->engine->render($view, $params);
    }

}
