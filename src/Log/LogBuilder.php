<?php

namespace Ken\Log;

use Exception;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class LogBuilder
{
    /**
     * Build string that will be logged. This method is used
     * internally by log() method.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    public static function buildLog(array $messages)
    {
        list($time, $level, $message, $context) = $messages;

        $ucLevel = strtoupper($level);

        $realMessage = self::applyContext($message, $context);

        $log = self::addTimestamps($time);
        $log .= self::addLevel($level);
        $log .= $realMessage;
        $log .= self::addException($context);
        $log .= self::addNewLine();

        return $log;
    }

    /**
     * Add Exception trace into log message if found in the context.
     *
     * @param array $context
     */
    private static function addException(array $context)
    {
        if (array_key_exists('exception', $context)) {
            if ($context['exception'] instanceof Exception) {
                $strException = $context['exception']->getTraceAsString();

                return self::addNewLine().$strException;
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
    private static function addLevel(string $level)
    {
        return '['.strtoupper($level).'] ';
    }

    /**
     * Returns new line character = ("\n").
     *
     * @return string Returns new line character = ("\n")
     */
    private static function addNewLine()
    {
        return "\n";
    }

    /**
     * Returns timestamp in string formatted '[$timestamp]' used in logging message.
     *
     * @return string Timestamp in the format '[Y-m-d H:i:s]'
     */
    private static function addTimestamps($timestamp)
    {
        return '['.date('Y-m-d H:i:s', $timestamp).'] ';
    }

    /**
     * Apply context into logging message.
     *
     * @param string $message
     * @param array  $context [optional]
     *
     * @return string
     */
    private static function applyContext(string $message, array $context = array())
    {
        foreach ($context as $key => $value) {
            if ($key != 'exception') {
                $message = self::replaceContext($message, $key, $value);
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
    private static function replaceContext(string $message, string $key, $context)
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
}
