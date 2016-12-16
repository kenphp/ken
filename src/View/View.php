<?php

namespace Ken\View;

use Ken\Base\Component;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class View extends Component
{
    private $_engine;

    public function __construct(array $config = array())
    {
        if (!isset($config['engine'])) {
            $config['engine'] = 'Ken\View\PhpEngine';
        }

        $this->initEngine($config);
    }

    private function initEngine($config)
    {
        $engineClass = $config['engine'];
        $this->_engine = $engineClass::build($config);
    }

    /**
     * Render a view file.
     *
     * @param string $view   Path of view file started from 'views' directory
     * @param array  $params Assosiative array containing parameters to be passed to view
     */
    public function render($view, array $params = [])
    {
        return $this->_engine->render($view, $params);
    }
}
