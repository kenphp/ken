<?php

namespace Ken\Exception;

use Exception;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class DuplicateRouteNameException extends Exception
{
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}
