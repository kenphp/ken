<?php

namespace Ken\Log;

use Ken\Exception\InvalidConfigurationException;
use Psr\Log\InvalidArgumentException;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class Logger extends AbstractLogger
{
    /**
     * Available Log Level.
     */
    private $_availableLevels = array('emergency', 'alert', 'critical',
                                        'error', 'warning', 'notice',
                                        'info', 'debug', );

    /**
     * Log Level that should be processed. Empty array means every log message will be ignored.
     */
    private $_enabledLevels = array();

    private $_messages = array();

    private $_flushInterval = 100;

    private $_targets;

    public function __construct(array $config = array())
    {
        $this->applyConfig($config);
    }

    private function applyConfig($config)
    {
        try {
            if (isset($config['enabledLevels'])) {
                $this->setEnabledLevels($config['enabledLevels']);
            } else {
                $this->setEnabledLevels(['error', 'warning']);
            }

            if (isset($config['flushIntervals'])) {
                $this->setFlushIntervals($config['flushIntervals']);
            } else {
                $this->setFlushIntervals(100);
            }

            if (isset($config['targets'])) {
                foreach ($config['targets'] as $key => $value) {
                    if (isset($value['class'])) {
                        $className = $value['class'];
                        $this->_targets[$key] = $className::build($value);
                    }
                }
            }
        } catch (InvalidConfigurationException $e) {
            $configStr = print_r($config, true);

            error_log('Invalid Configuration for Logger Component. '.PHP_EOL.$configStr);
        }
    }

    public static function build(array $config = array())
    {
        return new static($config);
    }

    /**
     * {@inheritdoc}
     */
    public function registerTarget(TargetInterface $target)
    {
        $this->_targets[] = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function unregisterTarget(TargetInterface $target)
    {
        for ($i = 0; $i < count($this->_targets); ++$i) {
            if ($this->_targets[$i] === $target) {
                unset($this->_targets[$i]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($messages)
    {
        foreach ($this->_targets as $target) {
            if ($target->isEnabled()) {
                $target->collect($messages);
            }
        }
    }

    /**
     * Flushes the messages to the targets.
     */
    public function flush()
    {
        $messages = $this->_messages;

        $this->_messages = [];

        $this->dispatch($messages);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        if (!$this->validLevel($level)) {
            throw new InvalidArgumentException("Invalid log level : $level");
        }

        if ($this->isLevelEnabled($level)) {
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $message = [$timestamp, $level, $message, $context];
            $this->_messages[] = $message;

            if ($this->_flushInterval > 0 && count($this->_messages) >= $this->_flushInterval) {
                $this->flush();
            }
        }
    }

    /**
     * Sets flush intervals.
     *
     * @param int $interval
     */
    public function setFlushIntervals(int $interval)
    {
        $this->_flushInterval = $interval;
    }

    /**
     * Sets enabled logging levels.
     *
     * @param array $levels
     */
    public function setEnabledLevels(array $levels)
    {
        $enabledLevels = array();

        foreach ($levels as $value) {
            if ($this->validLevel($value)) {
                array_push($enabledLevels, $value);
            }
        }

        $this->_enabledLevels = $enabledLevels;
    }

    /**
     * Checks whether logging level is valid.
     *
     * @param string $level
     *
     * @return bool True, if $level is a valid logging level.
     *              False, if otherwise
     */
    protected function validLevel(string $level)
    {
        return in_array($level, $this->_availableLevels);
    }

    /**
     * Checks whether logging level is enabled.
     *
     * @param string $level
     *
     * @return bool True, if $level is a enabled.
     *              False, if otherwise
     */
    protected function isLevelEnabled(string $level)
    {
        return in_array($level, $this->_enabledLevels);
    }
}
