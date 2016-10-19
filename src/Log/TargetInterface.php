<?php

namespace Ken\Log;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
interface TargetInterface
{
    /**
     * Retrieves log messages from Logger.
     *
     * @param array $messages
     */
    public function collect(array $messages);

    /**
     * Enables or Disables target.
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled);

    /**
     * Checks whether target is enabled.
     */
    public function isEnabled();
}
