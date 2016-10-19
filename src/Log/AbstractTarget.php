<?php

namespace Ken\Log;

use Ken\Base\Buildable;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class AbstractTarget implements TargetInterface, Buildable
{
    protected $enabled = true;

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    public function build(array $config = array()) {
        return new static($config);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function collect(array $messages);

}
