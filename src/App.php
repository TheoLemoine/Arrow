<?php

namespace Arrow;

use Arrow\Exceptions\ActionException;
use Arrow\Interfaces\IAction;
use Arrow\Internals\ServiceProvider;
use ReflectionClass;
use ReflectionException;

/**
 * Class App
 * @package Arrow
 *
 * The App class, used to launch your Actions tree
 *
 */
class App {

    private ServiceProvider $serviceProvider;

    public function __construct()
    {
        $this->serviceProvider = new ServiceProvider();
    }

    /**
     * @param IAction $startNode the initialized node to run
     * @throws Exceptions\ServiceException
     * @throws Exceptions\ActionException
     * @throws ReflectionException
     */
    public function run(IAction $startNode)
    {
        $this->_run($startNode);
        $this->serviceProvider->shutdownAllServices();
    }

    /**
     * @param IAction $action
     * @throws Exceptions\ServiceException
     * @throws Exceptions\ActionException
     * @throws ReflectionException
     */
    private function _run(?IAction $action)
    {
        if($action === null)
            return;

        if(!($action instanceof IAction))
            throw new ActionException('yielded object is not an action');

        $actionClass = new ReflectionClass($action);
        $this->serviceProvider->provideWithServices($action, $actionClass);

        foreach($action->run() as $newAction)
        {
            $this->_run($newAction);
        }
    }
}