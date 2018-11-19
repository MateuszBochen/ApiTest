<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 17.11.18
 * Time: 19:22
 */

namespace Library\DependencyInjection;


class ClassLoader extends Container
{
    /**
     * @param string $className
     * @param array|null $additionalParams
     * @return mixed
     * @throws \ReflectionException
     */
    public function loadClass(string $className, array $additionalParams = null)
    {
        if (!method_exists($className, '__construct')) {
            return $this->add($className, new $className());
        }

        $refMethod = new \ReflectionMethod($className, '__construct');
        $params = $refMethod->getParameters();

        /*if ($additionalParams !== null) {
            $params = array_merge($params, $additionalParams);
        }*/

        if (empty($params)) {
            return $this->add($className, new $className());
        }

        $prepareFunctionArguments = new PrepareFunctionArguments($params, $additionalParams);

        $refClass = new \ReflectionClass($className);
        $classInstance = $refClass->newInstanceArgs($prepareFunctionArguments->getArguments());

        if($prepareFunctionArguments->allArgumentsResolve()) {
            return $this->add($className, $classInstance);
        } else {
            return $classInstance;
        }

    }
}
