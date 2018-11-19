<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 14.11.18
 * Time: 19:10
 */

namespace Library\AutoLoader;

class AutoLoader
{
    public function register()
    {
        $autoload = array($this, 'load');
        \spl_autoload_register($autoload);
    }

    /**
     * @param $class
     * @throws AutoLoaderException
     */
    public function load($class)
    {
        $fileName = ROOT_DIR.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (!file_exists($fileName)){
            throw new AutoLoaderException('File <i>'.$fileName.'</i> does not exist');
        }
        require_once $fileName;
    }
}
