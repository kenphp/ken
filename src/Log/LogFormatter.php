<?php

namespace Ken\Log;

use Ken\Base\Buildable;

class LogFormatter implements FormatterInterface, Buildable {

    const FORMAT_TIMESTAMP = '%timestamp%';
    const FORMAT_LEVEL = '%level%';
    const FORMAT_MESSAGE = '%message%';
    const FORMAT_EXCEPTION = '%exception%';

    /**
     * Default to  "%timestamp% %level% %message%\n%exception%"
     * @var string
     */
    protected $logFormat;

    /**
     * Default to 'Y-m-d H:i:s'
     * @see http://php.net/manual/en/function.date.php
     * @var string
     */
    protected $dateTimeFormat;

    /**
     * @param string $logFormat Default to  "%timestamp% %level% %message%\n%exception%"
     * @param string $dateTimeFormat Default to 'Y-m-d H:i:s'
     * @see http://php.net/manual/en/function.date.php
     */
    public function __construct($logFormat = "%timestamp% %level% %message%\n%exception%",
                                $dateTimeFormat = 'Y-m-d H:i:s') {
        $this->logFormat = $logFormat;
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * @inheritDoc
     */
    public static function build($config = []) {
        extract($config);

        if (isset($logFormat)) {
            if (isset($dateTimeFormat)) {
                return new static($logFormat, $dateTimeFormat);
            }
            return new static($logFormat);
        }

        if (isset($dateTimeFormat)) {
            $logFormat = "%timestamp% %level% %message%\n%exception%";
            return new static($logFormat, $dateTimeFormat);
        }

        return new static();
    }

    /**
     * Format log message
     * @param  array  $logMessage An array with key `timestamp`, 'level', 'message', and 'context'
     * @return string
     */
    public function formatLog(array $logMessage) {
        extract($logMessage);

        $level = strtoupper($level);
        $message = $this->applyContext($message, $context);
        $timestamp = date($this->dateTimeFormat, $timestamp);
        $exception = $this->getExceptionTrace($context);

        $log = str_replace(self::FORMAT_TIMESTAMP, $timestamp, $this->logFormat);
        $log = str_replace(self::FORMAT_LEVEL, $level, $log);
        $log = str_replace(self::FORMAT_MESSAGE, $message, $log);
        $log = str_replace(self::FORMAT_EXCEPTION, $exception, $log);

        return $log;
    }

    /**
     * Apply context into log message.
     *
     * @param string $message
     * @param array  $context [optional]
     *
     * @return string
     */
    protected function applyContext($message, array $context = array())
    {
        foreach ($context as $key => $value) {
            $key = trim($key, "{}");
            if ($key != 'exception') {
                $message = $this->replaceContext($message, $key, $value);
            }
        }

        return $message;
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
    protected function replaceContext($message, string $key, $context)
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
     * Get Exception trace if found in the context.
     *
     * @param array $context
     */
    protected function getExceptionTrace(array $context)
    {
        if (isset($context['exception'])) {
            if ($context['exception'] instanceof \Exception || $context['exception'] instanceof \Error) {
                $strException = $context['exception']->getTraceAsString();

                return $strException;
            }
        }

        return '';
    }

}
