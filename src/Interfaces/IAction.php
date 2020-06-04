<?php

namespace Arrow\Interfaces;

use Generator;


/**
 * Interface IAction
 * @package Arrow\Interfaces
 *
 * Base Interface for Action implementation.
 *
 */
interface IAction {

    /**
     *
     * Override to take any parameters you want
     *
     * IAction constructor.
     */
    function __construct();

    /**
     *
     * Execute the Action, and yields other Actions or null
     *
     * @return Generator
     */
    function run(): Generator;

}