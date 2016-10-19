<?php

namespace Ken\Log\Targets;

use Ken\Exception\InvalidConfigurationException;
use Ken\Log\LogBuilder;
use Ken\Log\AbstractTarget;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class FileTarget extends AbstractTarget
{
    /**
     * @var string Path of log file
     */
    private $_filepath = '';

    public function __construct(array $config)
    {
        if (!isset($config['filepath'])) {
            throw new InvalidConfigurationException("Parameter 'filepath' not found");
        }

        $this->_filepath = $config['filepath'];

        if (isset($config['enabledLevels'])) {
            $this->enabledLevels = $config['enabledLevels'];
        } else {
            $this->enabledLevels = ['warning', 'error'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collect(array $messages)
    {
        $logMessages = '';

        foreach ($messages as $value) {
            $logMessages .= LogBuilder::buildLog($value);
        }

        $this->writeLog($logMessages);
    }

    /**
     * Writes log to media.
     *
     * @param string $log
     */
    private function writeLog(string $log)
    {
        $fh = fopen($this->_filepath, 'a');

        fwrite($fh, $log);
        fclose($fh);
    }
}
