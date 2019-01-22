<?php

namespace Ken\Log;

interface FormatterInterface {
    /**
     * Format log message
     * @param  array  $logMessage An array with key `timestamp`, 'level', 'message', and 'context'
     * @return string
     */
    public function formatLog(array $logMessage);
}
