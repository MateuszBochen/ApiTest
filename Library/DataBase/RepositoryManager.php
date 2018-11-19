<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 14:26
 */

namespace Library\DataBase;

use Library\DataBase\MySql\Driver;

class RepositoryManager
{
    private $diver;

    public function __construct(Driver $driver)
    {
        $this->diver = $driver;
    }

    public function getRepository($name) : Repository
    {
        if (is_object($name)) {
            $nameString = get_class($name);
        } else {
            $nameString = $name;
            $name = new $name();
        }


        $modelName = Helper::getModelName($nameString);

        $repositoryClass = '\Repository\\'.$modelName.'Repository';

        return new $repositoryClass($this->diver, $name);
    }


}
