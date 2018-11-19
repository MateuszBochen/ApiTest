<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 17.11.18
 * Time: 19:34
 */

namespace Library\DependencyInjection;


class Container
{
    /**
     * collection of services
     *
     * @type array
     */
    protected static $collection = [];

    public function has(string $className) {
        return isset(self::$collection[$className]);
    }


    /**
     * @param string $className
     * @return mixed
     * @throws \ReflectionException
     */
    public function get(string $className)
    {
        if (isset(self::$collection[$className])) {
            return self::$collection[$className];
        }

        $classLoader = new ClassLoader();
        $class = $classLoader->loadClass($className);

        return $class;
    }

    protected function add(string $className, $object)
    {
        self::$collection[$className] = $object;

        return $object;
    }


}
