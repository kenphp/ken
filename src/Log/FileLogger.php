<?php

namespace Ken\Log;

use Ken\Exception\InvalidConfigurationException;

/**
 * Class Logger for File.
 *
 * @author Juliardi <ardi93@gmail.com>
 */
class FileLogger extends BaseLogger
{
    protected $filepath = '';

    public function __construct(array $config)
    {
        if (!isset($config['filepath'])) {
            throw new InvalidConfigurationException("Parameter 'filepath' not found");
        }

        $this->filepath = $config['filepath'];

        if (isset($config['enabledLevels'])) {
            $this->enabledLevels = $config['enabledLevels'];
        } else {
            $this->enabledLevels = ['warning', 'error'];
        }
    }

    protected function writeLog(string $log)
    {
        $fileHandle = fopen($this->filepath, 'a');

        fwrite($fileHandle, $log);
        fclose($fileHandle);
    }

    /**
     * Set path of log file.
     *
     * @param string $filepath Path of log file
     *
     * @author Juliardi [juliardi93@gmail.com]
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }
}
