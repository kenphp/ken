<?php

namespace Ken;

use Ken\Exception\FileNotFoundException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class View
{
    /**
     * @var Ken\View\BaseView
     */
    private $engine;

    public function __construct($config)
    {
        if (!isset($config['engine'])) {
            $config['engine'] = 'Ken\\View\\PhpEngine';
        }

        $this->initEngine($config);
    }

    protected function initEngine($config)
    {
        $this->engine = $config['engine']::newFromConfig($config['engine'], $config);
    }

    public function render(string $view, array $params = [])
    {
        try {
            $this->engine->render($view, $params);
        } catch (FileNotFoundException $exc) {
            app()->logger->error($exc->getMessage());
        }
    }
}
