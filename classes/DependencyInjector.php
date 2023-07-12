<?php namespace Waka\Wakajob\Classes;

use Illuminate\Contracts\Container\Container;
use Waka\Wakajob\Contracts\NeedsDependencies;
use Winter\Storm\Exception\ApplicationException;

/**
 * Class DependencyInjector
 *
 * @package Waka\Wakajob\Classes
 */
class DependencyInjector
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * DependencyInjector constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param object $object
     * @throws ApplicationException
     */
    public function injectDependencies($object)
    {
        if (!$object instanceof NeedsDependencies) {
            return;
        }

        foreach (get_class_methods($object) as $method) {
            if (strpos($method, 'inject') === 0) {
                try {
                    $this->container->call([$object, $method]);
                } catch (\Exception $e) {
                    $msg = $e->getMessage() . ' at class: '. \get_class($object);
                    throw new ApplicationException($msg);
                }
            }
        }
    }
}
