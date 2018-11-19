<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 14.11.18
 * Time: 20:34
 */

namespace Library\Controller;

use Library\DependencyInjection\Container;

abstract class BaseController
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $className
     * @return mixed
     * @throws \ReflectionException
     */
    public function get($className)
    {
        return $this->container->get($className);
    }

    public function createForm($dataObject)
    {
        $fc = $this->get(\Library\Form\FormCreator::class);
        return $fc->create($dataObject);
    }

    public function getRepository($model) {
        $rm = $this->get(\Library\DataBase\RepositoryManager::class);
        return $rm->getRepository($model);
    }

}
