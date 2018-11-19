<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 17.11.18
 * Time: 20:56
 */

namespace Library\DependencyInjection;


class MethodCaller
{
    /**
     * @param $object
     * @param $methodName
     * @return mixed
     * @throws MethodCallerException
     * @throws \ReflectionException
     */
    public function call($object, $methodName)
    {
        if(!method_exists($object, $methodName)) {
            throw new MethodCallerException($methodName);
        }

        $refMethod = new \ReflectionMethod($object,  $methodName);
        $params = $refMethod->getParameters();

        if(empty($params)) {
            return $object->$methodName();
        }

        $prepareFunctionArguments = new PrepareFunctionArguments($params);

        return call_user_func_array([$object, $methodName], $prepareFunctionArguments->getArguments());
    }
}
