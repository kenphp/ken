<?php

namespace Ken\View;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class ViewFactory
{
    /**
     * Create view engine. If $config['class'] is not set, then the default
     * is Ken\View\PhpEngine.
     *
     * @param array $config View config
     *
     * @return Ken\View\BaseView
     */
    public static function createViewEngine(array $config)
    {
        if (!isset($config['class'])) {
            $config['class'] = 'Ken\\View\\PhpEngine';
        }

        return $config['class']::newFromConfig($config['class'], $config);
    }
}
