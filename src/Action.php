<?php


namespace Arrow;

use Arrow\Exceptions\ActionException;
use Arrow\Interfaces\IAction;
use Generator;


/**
 * Class Action
 * @package Arrow
 *
 * Base class for Actions
 * Makes implementation of IAction Methods optional
 *
 */
abstract class Action implements IAction {

    /**
     * @inheritDoc
     */
    public function __construct()
    {}

    /**
     * @inheritDoc
     * @throws ActionException throws an exception if run was not implemented
     */
    public function run(): Generator
    {
        throw new ActionException('run() method was not implemented on ' . static::class);
    }

}