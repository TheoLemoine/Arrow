<?php


namespace Arrow\Exceptions;

use Exception;


/**
 * Class ArrowException
 * @package Arrow\Exceptions
 *
 * Base class for internal Arrow exceptions
 *
 */
class ArrowException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}