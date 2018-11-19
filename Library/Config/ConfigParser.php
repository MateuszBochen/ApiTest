<?php
/**
 * Created by PhpStorm.
 * User: backen
 * Date: 18.11.18
 * Time: 13:00
 */

namespace Library\Config;


class ConfigParser
{
    const CONFIG_FILE = '/config/config.yml';

    private static $configIsLoaded = false;

    private static $configParams;


    public function getParam(string $paramName)
    {
        if(self::$configIsLoaded === false) {
            $this->loadConfig();
        }

        if(isset(self::$configParams[$paramName])) {
            return self::$configParams[$paramName];
        }

        return null;
    }

    private function loadConfig()
    {
        $configData = yaml_parse_file(ROOT_DIR.self::CONFIG_FILE, 0);
        self::$configParams = $configData;
        self::$configIsLoaded = true;
    }
}
