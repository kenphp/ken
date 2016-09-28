<?php

namespace Ken\Log;

use Psr;
use Exception;
use Psr\Log\AbstractLogger;

/**
 * This is a base class for all Logger class.
 *
 * @author juliardi [juliardi93@gmail.com]
 */
abstract class BaseLogger extends AbstractLogger
{
    /**
     * Available Log Level.
     */
    protected $availableLevel = array('emergency', 'alert', 'critical',
                                        'error', 'warning', 'notice',
                                        'info', 'debug', );

    /**
     * Log Level that should be processed. Empty array means every log message will be ignored.
     */
    protected $enabledLevels = array();

    public function log($level, $message, array $context = array())
    {
        if (!$this->checkLevel($level)) {
            throw new Psr\Log\InvalidArgumentException("Invalid log level : $level");
        }

        if ($this->isLevelEnabled($level)) {
            $this->buildLog($level, $message, $context);
        }
    }

    /**
     * Set enabled logging levels.
     *
     * @param array $levels
     */
    public function setEnabledLevels(array $levels)
    {
        $enabledLevels = array();

        foreach ($levels as $value) {
            if ($this->checkLevel($value)) {
                array_push($enabledLevels, $value);
            }
        }

        $this->enabledLevels = $enabledLevels;
    }

    /**
     * Build string that will be logged. This method is used
     * internally by log() method.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    protected function buildLog(string $level, string $message, array $context = array())
    {
        $ucLevel = strtoupper($level);

        $realMessage = $this->applyContext($message, $context);

        $log = $this->addTimestamps();
        $log .= $this->addLevel($level);
        $log .= $realMessage;
        $log .= $this->addException($context);
        $log .= $this->addNewLine();

        $this->writeLog($log);
    }

    /**
     * Add Exception trace into log message if found in the context.
     *
     * @param array $context
     */
    protected function addException(array $context)
    {
        if (array_key_exists('exception', $context)) {
            if ($context['exception'] instanceof Exception) {
                $strException = $context['exception']->getTraceAsString();

                return $this->addNewLine().$strException;
            }
        }

        return '';
    }

    /**
     * Returns string '[$level]' used for logging message.
     *
     * @param string $level
     *
     * @return string Logging level in the format '[$level]'
     */
    protected function addLevel(string $level)
    {
        return '['.strtoupper($level).'] ';
    }

    /**
     * Returns new line character = ("\n").
     *
     * @return string Returns new line character = ("\n")
     */
    protected function addNewLine()
    {
        return "\n";
    }

    /**
     * Returns timestamp in string formatted '[$timestamp]' used in logging message.
     *
     * @return string Timestamp in the format '[Y-m-d H:i:s]'
     */
    protected function addTimestamps()
    {
        return '['.date('Y-m-d H:i:s').'] ';
    }

    /**
     * Apply context into logging message.
     *
     * @param string $message
     * @param array  $context [optional]
     *
     * @return string
     */
    protected function applyContext(string $message, array $context = array())
    {
        foreach ($context as $key => $value) {
            if ($key != 'exception') {
                $message = $this->replaceContext($message, $key, $value);
            }
        }

        return $message;
    }

    /**
     * Check whether logging level is valid.
     *
     * @param string $level
     *
     * @return bool True, if $level is a valid logging level.
     *              False, if otherwise
     */
    protected function checkLevel(string $level)
    {
        return in_array($level, $this->availableLevel);
    }

    /**
     * Check whether logging level is enabled.
     *
     * @param string $level
     *
     * @return bool True, if $level is a enabled.
     *              False, if otherwise
     */
    protected function isLevelEnabled(string $level)
    {
        return in_array($level, $this->enabledLevels);
    }

    /**
     * Replace context pattern in the message with related context.
     *
     * @param string $message Log message
     * @param string $key     Context pattern to be replaced
     * @param mixed  $context Related context to replace $key
     *
     * @return string $message with pattern '{$key}' replaced by $context
     */
    protected function replaceContext(string $message, string $key, $context)
    {
        $strContext = '';
        if (is_object($context)) {
            if (method_exists($context, '__toString')) {
                $strContext = call_user_func([$context, '__toString']);
            } else {
                $strContext = var_export($context, true);
            }
        } else {
            $strContext = $context;
        }

        return str_replace('{'.$key.'}', $strContext, $message);
    }

    /**
     * Write log into media.
     *
     * @param string $log
     */
    abstract protected function writeLog(string $log);
}
