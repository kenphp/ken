<?php

namespace Ken\Log\Targets;

use Ken\Exception\InvalidConfigurationException;
use Ken\Log\LogFormatter;
use Ken\Log\AbstractTarget;
use Ken\Log\FormatterInterface;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class FileTarget extends AbstractTarget
{
    /**
     * @var string Path of log file
     */
    private $_filepath = '';

    /**
     * Log formatter
     * @var \Ken\Log\FormatterInterface
     */
    private $_formatter;

    public function __construct(array $config)
    {
        if (!isset($config['filepath'])) {
            throw new InvalidConfigurationException("Parameter 'filepath' not found");
        }

        if (isset($config['formatter'])) {
            if ($config['formatter'] instanceof FormatterInterface) {
                $this->_formatter = $config['formatter'];
            } else {
                $this->_formatter = LogFormatter::build($config['formatter']);
            }
        } else {
            $this->_formatter = LogFormatter::build();
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
            $logMessages .= $this->_formatter->formatLog($value) . "\n";
        }

        $this->writeLog($logMessages);
    }

    /**
     * Writes log to media.
     *
     * @param string $log
     */
    private function writeLog($log)
    {
        $fh = fopen($this->_filepath, 'a');

        fwrite($fh, $log);
        fclose($fh);
    }
}
