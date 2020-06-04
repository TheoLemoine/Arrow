<?php

namespace Arrow\Interfaces;

/**
 * Interface IService
 * @package Arrow\Interfaces
 *
 * Base Interface for Service implementations
 *
 */
interface IService {
    
    /**
     * lifecycle method called when Service is initialized
     * 
     * Use for subscribing to events, setting up things, ...
     */
    function startup();

    /**
     * lifecycle method called when App has ended running
     * 
     * Use for closing connections, freeing resources
     * although you can do that in __destruct().
     */
    function shutdown();

}