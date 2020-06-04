<?php


namespace Arrow\Internals;

use Arrow\Exceptions\ServiceException;
use Arrow\Interfaces\IService;
use ReflectionClass;
use ReflectionException;
use TypeError;


/**
 * Class ServiceProvider
 * @package Arrow\Internals
 *
 * Service provider, uses reflection to fill classes with requested services
 *
 */
class ServiceProvider
{
    /** @var IService[] */
    private array $services = [];

    /** @var string[] */
    private array $waitingServices = [];

    /**
     *
     * Provide a service, initialise it if it does not yet exist
     *
     * @param string $serviceClassName
     * @return IService
     * @throws ServiceException
     */
    public function getService(string $serviceClassName) : IService
    {
        try {
            $serviceClass = new ReflectionClass($serviceClassName);
        } catch (ReflectionException $e) {
            throw new TypeError('Requested name is not a class');
        }

        if(!$serviceClass->implementsInterface(IService::class))
            throw new TypeError('Requested class must implement ' . IService::class);

        if(!$serviceClass->isFinal())
            throw new ServiceException('Required services should be declared as final');

        if(isset($this->services[$serviceClassName]))
            return $this->services[$serviceClassName];

        if(in_array($serviceClassName, $this->waitingServices))
            throw new ServiceException('Circular service reference found !');


        /** @var IService $newService */
        $newService = new $serviceClassName();

        $this->addWaitingService($serviceClassName);
        $this->provideWithServices($newService, $serviceClass);
        $newService->startup();
        $this->setServiceReady($serviceClassName, $newService);

        return $newService;
    }

    public function shutdownAllServices() {
        foreach ($this->services as $service) {
            $service->shutdown();
        }
    }

    /**
     *
     * Inject all his other services dependencies in a service
     *
     * @param object $object
     * @param ReflectionClass $classReflection
     * @throws ServiceException
     */
    public function provideWithServices(object $object, ReflectionClass $classReflection)
    {
        foreach ($classReflection->getProperties() as $propertyReflection)
        {
            if (!$propertyReflection->hasType()) continue;

            $propertyTypeName = $propertyReflection->getType()->getName();

            if($implements = class_implements($propertyTypeName)) {
                if(!in_array(IService::class , $implements))
                    // Service interface is not implemented
                    continue;
            } else {
                // is not a class or does not implements anything
                continue;
            }

            $requestedService = $this->getService($propertyTypeName);

            $propertyReflection->setValue($object, $requestedService);
        }
    }

    /**
     *
     * adds a service to the stack of services initializing to detect circular reference
     *
     * @param string $serviceClassName
     */
    private function addWaitingService(string $serviceClassName)
    {
        $this->waitingServices[] = $serviceClassName;
    }

    /**
     *
     * remove a service from the stack and add it to the provider
     *
     * @param string $serviceClassName
     * @param IService $readyService
     */
    private function setServiceReady(string $serviceClassName, IService $readyService)
    {
        $key = array_search($serviceClassName, $this->waitingServices);
        unset($this->waitingServices[$key]);

        $this->services[] = $readyService;
    }

}