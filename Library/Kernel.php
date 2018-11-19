<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 14.11.18
 * Time: 19:36
 */

namespace Library;

use Library\Http\Request;
use Library\DependencyInjection\ClassLoader;
use Library\DependencyInjection\Container;
use Library\DependencyInjection\MethodCaller;
use Library\Routing\Routing;

class Kernel
{
    private $request;

    /**
     * @throws DependencyInjection\MethodCallerException
     * @throws \ReflectionException
     */
    public function init()
    {
        $container = new Container();

        $this->request = $container->get(Request::class);

        $routing = $container->get(Routing::class);
        $routing->getController($this->request->getCurrentUrl());

        $controllerName = $routing->getController($this->request->getCurrentUrl());

        $actionName = strtolower($this->request->getMethod()).ucfirst($routing->getMethod());

        $this->runController($controllerName, $actionName);
    }

    /**
     * @param string $name
     * @param string $action
     * @throws \ReflectionException
     * @throws DependencyInjection\MethodCallerException
     */
    private function runController(string $name, string $action)
    {
        $className = 'Controller\\'.ucfirst($name).'Controller';
        $actionName = $action.'Action';

        $classLoader = new ClassLoader();
        $controller = $classLoader->loadClass($className);

        $methodCaller = new MethodCaller();
        $response = $methodCaller->call($controller, $actionName);

        $response->response();
    }
}
