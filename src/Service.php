<?php

namespace Arrow;

use Arrow\Interfaces\IService;


/**
 * Class Service
 * @package Arrow\Base\Services
 *
 * Base class for Services
 * Makes declaration of IService methods optional
 *
 */
abstract class Service implements IService
{

    /**
     * @inheritDoc
     */
    function startup()
    {}

    /**
     * @inheritDoc
     */
    function shutdown()
    {}
}