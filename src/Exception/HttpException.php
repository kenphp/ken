<?php

namespace Ken\Exception;

use Exception;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class HttpException extends Exception
{
    public function __construct($code, $message = '')
    {
        $this->code = $code;
        parent::__construct($message, $code);
    }
}
