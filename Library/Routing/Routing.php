<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 17:08
 */

namespace Library\Routing;


class Routing
{
    const ROUTING_FILE = '/config/routing.yml';

    private static $routingIsLoaded = false;
    private static $routing = [];

    private $method = '';
    private $controller = '';
    private $requestParams = [];

    public function getController(string $route)
    {
        if (!self::$routingIsLoaded) {
            $this->loadRouting();
        }

        $this->match($route);

        return $this->controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRequestParams() : array
    {
        return $this->requestParams;
    }

    /**
     * @param string $route
     * @return string
     * @throws RoutingException
     */
    private function match(string $route)
    {
        $routeArray = explode('/', trim($route,'/'));
        foreach(self::$routing as $key => $value) {
            $definedRouteArray = explode('/', trim($key, '/'));
            $params = $this->matchRouteArrays($routeArray, $definedRouteArray);
            if($params !== false) {
                $this->controller = $value['controller'];
                if (isset($value['method'])) {
                    $this->method = $value['method'];
                }

                return;
            }
        }

        throw new RoutingException();
    }

    /**
     * @param array $routeArray
     * @param array $definedRouteArray
     * @return array|bool
     */
    private function matchRouteArrays(array $routeArray, array $definedRouteArray)
    {
        $mach = [];
        foreach($definedRouteArray as $key => $value){
            $pattern = preg_replace('/:([0-9a-z]+)/i', '([0-9a-z]+)', $value);

            if(!isset($routeArray[$key])) {
                return false;
            }

            $compare = $routeArray[$key];
            if(preg_match("/$pattern/i", $compare)) {
                $mach[trim($value, ':')] = $compare;
            }
        }

        if(count($routeArray) === count($mach)) {
            $this->requestParams = $mach;
            return $mach;
        }

        return false;
    }

    private function loadRouting()
    {
        $routingData = yaml_parse_file(ROOT_DIR.self::ROUTING_FILE, 0);
        self::$routing = $routingData;
        self::$routingIsLoaded = true;
    }
}